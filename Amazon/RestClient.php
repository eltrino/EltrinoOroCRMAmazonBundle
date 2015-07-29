<?php

namespace Eltrino\OroCrmAmazonBundle\Amazon;

use Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Response;

class RestClient
{
    const GET_SERVICE_STATUS             = 'GetServiceStatus';
    const LIST_ORDERS                    = 'ListOrders';
    const LIST_ORDER_ITEMS               = 'ListOrderItems';
    const LIST_ORDERS_BY_NEXT_TOKEN      = 'ListOrdersByNextToken';
    const LIST_ORDER_ITEMS_BY_NEXT_TOKEN = 'ListOrderItemsByNextToken';

    const ACTION_PARAM     = 'Action';
    const NEXT_TOKEN_PARAM = 'NextToken';

    const STATUS_GREEN = 'GREEN';

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
     * @var array
     */
    protected $restoreRateRequests = [
        self::GET_SERVICE_STATUS => 0,
        self::LIST_ORDERS        => 0,
        self::LIST_ORDER_ITEMS   => 0
    ];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param ClientInterface      $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->client      = $client;
        $this->authHandler = $authHandler;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $action = $request->getAction();
        if (!isset(static::$throttlingParams[$action])) {
            throw new \InvalidArgumentException('Unknown action ' . $action);
        }
        $requestParameters = $this->createRequestParameters($action, $request);

        $shareAction = str_replace('ByNextToken', '', $action);
        $this->applyRecoveryRate($shareAction);
        $response = $this->client->post(null, [], $requestParameters)->send();

        if ($this->restoreRateRequests[$shareAction] === 0) {
            $this->requestsCounters[$shareAction]++;
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::SERVICE_VERSION;
    }

    /**
     * @param         $action
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

    /**
     * @param string $action
     */
    protected function applyRecoveryRate($action)
    {
        $restoreRateSeconds = static::$throttlingParams[$action]['restore_rate'];
        $maxRequestsQuota = static::$throttlingParams[$action]['max_requests_quota'];

        if ($this->restoreRateRequests[$action] > 0) {
            if ($this->restoreRateRequests[$action] == $maxRequestsQuota) {
                $this->restoreRateRequests[$action] = 0;
            } else {
                $this->useRecoveryRate($action, $restoreRateSeconds);
            }
        } else {
            if ($this->requestsCounters[$action] == $maxRequestsQuota) {
                if (($extraRequests = floor($this->requestsExtraTime[$action] / $restoreRateSeconds)) > 0) {
                    if ($extraRequests <= $this->requestsCounters[$action]) {
                        $this->requestsCounters[$action] = $this->requestsCounters[$action] - $extraRequests;
                        $this->requestsExtraTime[$action] = $this->requestsExtraTime[$action] % $restoreRateSeconds;
                    } else {
                        $this->requestsCounters[$action] = 0;
                        $this->requestsExtraTime[$action] = 0;
                    }
                } else {
                    $this->useRecoveryRate($action, $restoreRateSeconds);
                }
            }
        }
    }

    /**
     * @param $action
     * @param $restoreRateSeconds
     */
    protected function useRecoveryRate($action, $restoreRateSeconds)
    {
        sleep($restoreRateSeconds);
        $this->requestsCounters[$action]--;
        array_walk(
            $this->requestsExtraTime,
            function (&$val, $key) use ($action, $restoreRateSeconds) {
                if ($key !== $action) {
                    $val += $restoreRateSeconds;
                };
            }
        );
        $this->restoreRateRequests[$action]++;
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
