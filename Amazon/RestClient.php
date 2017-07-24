<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
namespace Eltrino\OroCrmAmazonBundle\Amazon;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

use Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;

use Guzzle\Common\Event;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Plugin\Backoff\BackoffPlugin;
use Guzzle\Http\ClientInterface;

class RestClient extends AbstractRestClient implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    
    const BACK_OFF_RETRIES  = 4;
    const SERVICE_VERSION   = '2013-09-01';

    /**
     * @var array
     *
     * 'max_requests_quota' => The maximum size that the request quota can reach.
     * 'restore_rate' => The rate at which your request quota increases over time, up to the maximum request quota.
     */
    protected static $throttlingParams = [
        self::GET_SERVICE_STATUS             => ['max_requests_quota' => 2, 'restore_rate' => 300],
        self::LIST_ORDERS                    => ['max_requests_quota' => 6, 'restore_rate' => 60],
        self::LIST_ORDERS_BY_NEXT_TOKEN      => ['max_requests_quota' => 6, 'restore_rate' => 60],
        self::LIST_ORDER_ITEMS               => ['max_requests_quota' => 30, 'restore_rate' => 2],
        self::LIST_ORDER_ITEMS_BY_NEXT_TOKEN => ['max_requests_quota' => 30, 'restore_rate' => 2],
    ];

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var AuthorizationHandler
     */
    protected $authHandler;

    /**
     * @var array
     * Store counters for api requests.
     * The number of requests that you can submit at one time without throttling
     * for each action
     */
    protected $requestsCounters = [
        self::GET_SERVICE_STATUS => 0,
        self::LIST_ORDERS        => 0,
        self::LIST_ORDER_ITEMS   => 0
    ];

    /**
     * @var array
     * Time in seconds which left from other actions recovery rate and
     * can be used to decrease request counter for current action
     */
    protected $requestsExtraTime = [
        self::GET_SERVICE_STATUS => 0,
        self::LIST_ORDERS        => 0,
        self::LIST_ORDER_ITEMS   => 0
    ];

    /**
     * @var string|null
     */
    protected $shareAction;

    /**
     * @param ClientInterface      $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->client      = $client;
        $this->authHandler = $authHandler;
        /**
         *  BackoffPlugin handle unexpected error responses(500 and 503) and will
         *  apply backoff exponential strategy and resend requests to the api.
         */
        $backoffPlugin = BackoffPlugin::getExponentialBackoff(self::BACK_OFF_RETRIES);
        $this->client->addSubscriber($backoffPlugin);
        $backoffPlugin->getEventDispatcher()->addListener(BackoffPlugin::RETRY_EVENT, [$this, 'onRetryEvent']);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(Request $request)
    {
        $action = $request->getAction();
        if (!isset(static::$throttlingParams[$action])) {
            throw new \InvalidArgumentException('Unknown action ' . $action);
        }
        $requestParameters = $this->createRequestParameters($action, $request);

        $this->shareAction = str_replace('ByNextToken', '', $action);
        $this->applyRecoveryRate();
        $this->logger->debug(sprintf(
                "[ELTAMZ] Sending request to '%s' with parameters '%s'",
                $this->client->getBaseUrl(),
                json_encode($requestParameters)
            ));
        $response = $this->client->post(null, [], $requestParameters)->send();
        $this->requestsCounters[$this->shareAction]++;

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return self::SERVICE_VERSION;
    }

    /**
     * Apply recovery rate on backoff plugin retry event.
     * @param Event $event
     */
    public function onRetryEvent(Event $event)
    {
        $this->applyRecoveryRate();
        $this->requestsCounters[$this->shareAction]++;
    }

    /**
     * @param string  $action
     * @param Request $request
     * @return array
     */
    protected function createRequestParameters($action, Request $request)
    {
        $requestParameters = array_merge(
            [
                'Action'             => $action,
                'SellerId'           => $this->authHandler->getMerchantId(),
                'MarketplaceId.Id.1' => $this->authHandler->getMarketplaceId(),
                'AWSAccessKeyId'     => $this->authHandler->getKeyId(),
                'Timestamp'          => $this->getFormattedTimestamp(),
                'Version'            => $this->getVersion(),
                'SignatureVersion'   => $this->authHandler->getSignatureVersion(),
                'SignatureMethod'    => $this->authHandler->getSignatureMethod(),
            ],
            $request->processFiltersParameters(),
            $request->getParameters()
        );
        $requestParameters['Signature'] = $this->authHandler->getSignature(
            $requestParameters,
            $this->client->getBaseUrl()
        );

        return $requestParameters;
    }

    protected function applyRecoveryRate()
    {
        $restoreRateSeconds = static::$throttlingParams[$this->shareAction]['restore_rate'];
        $maxRequestsQuota = static::$throttlingParams[$this->shareAction]['max_requests_quota'];
        if ($this->requestsCounters[$this->shareAction] >= $maxRequestsQuota) {
            if (($extraRequests = floor($this->requestsExtraTime[$this->shareAction] / $restoreRateSeconds)) > 0) {
                if ($extraRequests <= $this->requestsCounters[$this->shareAction]) {
                    $this->requestsCounters[$this->shareAction] -= $extraRequests;
                    $this->requestsExtraTime[$this->shareAction] =
                        $this->requestsExtraTime[$this->shareAction] % $restoreRateSeconds;
                } else {
                    $this->requestsCounters[$this->shareAction] = 0;
                    $this->requestsExtraTime[$this->shareAction] = 0;
                }
            } else {
                $this->useRecoveryRate($maxRequestsQuota * $restoreRateSeconds);
            }
        } else {
            if ($this->requestsExtraTime[$this->shareAction] > 0) {
                $extra = $this->requestsExtraTime[$this->shareAction];
                $this->requestsExtraTime[$this->shareAction] = $extra <= $restoreRateSeconds
                    ? 0
                    : $extra - $restoreRateSeconds;
            }
        }
    }

    /**
     * @param int    $restoreRateSeconds
     */
    protected function useRecoveryRate($restoreRateSeconds)
    {
        $this->logger->info(sprintf(
                "[ELTAMZ] Sleeping for %s seconds to restore request throttling",
                $restoreRateSeconds
            ));
        sleep($restoreRateSeconds);
        $this->logger->info(sprintf(
                "[ELTAMZ] Waking after %s seconds sleep",
                $restoreRateSeconds
            ));
        array_walk(
            $this->requestsExtraTime,
            function (&$val, $key) use ($restoreRateSeconds) {
                if ($key !== $this->shareAction) {
                    $val += $restoreRateSeconds;
                };
            }
        );
        $this->requestsCounters[$this->shareAction] = 0;
        $this->requestsExtraTime[$this->shareAction] = 0;
    }

    /**
     * Formats date as ISO 8601 timestamp
     * @return string
     */
    protected function getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }
}
