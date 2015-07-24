<?php

namespace OroCRM\Bundle\AmazonBundle\Client;

use Guzzle\Http\Message\Response;
use Guzzle\Http\ClientInterface;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

use OroCRM\Bundle\AmazonBundle\Client\Filters\FilterInterface;

class RestClient implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const GET_SERVICE_STATUS             = 'GetServiceStatus';
    const LIST_ORDERS                    = 'ListOrders';
    const LIST_ORDER_ITEMS               = 'ListOrderItems';
    const LIST_ORDERS_BY_NEXT_TOKEN      = 'ListOrdersByNextToken';
    const LIST_ORDER_ITEMS_BY_NEXT_TOKEN = 'ListOrderItemsByNextToken';

    const ACTION_PARAM     = 'Action';
    const NEXT_TOKEN_PARAM = 'NextToken';

    const STATUS_GREEN = 'GREEN';

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
     * @var AuthHandler
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
     */
    protected $parameters = [];

    /**
     * @param ClientInterface      $client
     * @param AuthHandlerInterface $authHandler
     */
    public function __construct(ClientInterface $client, AuthHandlerInterface $authHandler)
    {
        $this->client      = $client;
        $this->authHandler = $authHandler;
    }

    /**
     * @param string          $action
     * @param FilterInterface $filter
     * @param array           $parameters
     * @return RestClientResponse
     */
    public function requestAction($action, FilterInterface $filter = null, array $parameters = [])
    {
        if (!isset(static::$throttlingParams[$action])) {
            throw new \InvalidArgumentException('Unknown action ' . $action);
        }
        $requestParameters = $this->getRequestParameters($action, $filter, $parameters);

        $response = $this->client->post(null, [], $requestParameters)->send();
        $this->applyRecoveryRate(str_replace(self::NEXT_TOKEN_PARAM, '', $action));
        $namespace = $this->client->getBaseUrl() . '/' . $this->authHandler->getVersion();

        $result             = $response->xml()->children($namespace);
        $resultRoot         = $requestParameters[self::ACTION_PARAM] . 'Result';
        $restClientResponse = new RestClientResponse(
            $result,
            $resultRoot
        );
        if ($nextToken = (string)$result->{$resultRoot}->{self::NEXT_TOKEN_PARAM}) {
            $restClientResponse->setNextToken($nextToken);
        }

        return $restClientResponse;
    }

    /**
     * @param string               $action
     * @param FilterInterface|null $filter
     * @param array                $parameters
     * @return array
     */
    protected function getRequestParameters($action, FilterInterface $filter = null, array $parameters = [])
    {
        $filterParameters = [];
        if (null !== $filter) {
            $filterParameters = $filter->process();
        }
        $requestParameters = array_merge(
            [
                'Action'             => $action,
                'SellerId'           => $this->authHandler->getMerchantId(),
                'MarketplaceId.Id.1' => $this->authHandler->getMarketplaceId(),
                'AWSAccessKeyId'     => $this->authHandler->getKeyId(),
                'Timestamp'          => $this->authHandler->getFormattedTimestamp(),
                'Version'            => $this->authHandler->getVersion(),
                'SignatureVersion'   => $this->authHandler->getSignatureVersion(),
                'SignatureMethod'    => $this->authHandler->getSignatureMethod(),
            ],
            $parameters,
            $filterParameters
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
        $this->requestsCounters[$action]++;
        $maxRequestsQuota = static::$throttlingParams[$action]['max_requests_quota'];

        if ($this->requestsCounters[$action] === $maxRequestsQuota) {
            $restoreRate    = static::$throttlingParams[$action]['restore_rate'];
            $restoreSeconds = $restoreRate * $maxRequestsQuota;
            if (null !== $this->logger) {
                $this->logger->info(
                    sprintf('Sleeping to avoid throttling for %d secs', $restoreSeconds)
                );
            }
            sleep($restoreSeconds);
            $this->requestsCounters[$action] = 0;
        }
    }
}
