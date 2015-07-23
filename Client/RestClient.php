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

    const GET_SERVICE_STATUS_ACTION = 'GetServiceStatus';
    const LIST_ORDERS_ACTION        = 'ListOrders';
    const LIST_ORDERS_ITEMS_ACTION  = 'ListOrderItems';
    const BY_NEXT_TOKEN_SUF         = 'ByNextToken';
    const ACTION_PARAM              = 'Action';
    const NEXT_TOKEN_PARAM          = 'NextToken';
    const ACTION_RESULT_SUF         = 'Result';

    const STATUS_GREEN = 'GREEN';

    /**
     * @var array
     *
     * 'max_requests_quota' => The maximum size that the request quota can reach.
     * 'restore_rate' => The rate at which your request quota increases over time, up to the maximum request quota.
     */
    protected static $throttlingParams = [
        self::GET_SERVICE_STATUS_ACTION => ['max_requests_quota' => 2, 'restore_rate' => 300],
        self::LIST_ORDERS_ACTION        => ['max_requests_quota' => 6, 'restore_rate' => 60],
        self::LIST_ORDERS_ITEMS_ACTION  => ['max_requests_quota' => 30, 'restore_rate' => 2]
    ];

    /**
     * @var \SimpleXMLElement[]
     */
    protected $responses;

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
        self::GET_SERVICE_STATUS_ACTION => 0,
        self::LIST_ORDERS_ACTION        => 0,
        self::LIST_ORDERS_ITEMS_ACTION  => 0
    ];

    /**
     * @var array
     */
    protected $parameters = [];

    public function __construct(ClientInterface $client, AuthHandler $authHandler)
    {
        $this->client      = $client;
        $this->authHandler = $authHandler;
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');
    }

    /**
     * @param                 $action
     * @param FilterInterface $filter
     * @param array           $parameters
     * @return array
     */
    public function requestAction($action, FilterInterface $filter = null, array $parameters = [])
    {
        $this->responses = [];
        $this->processParameters($action, $filter, $parameters);
        $response = $this->formatResponse($this->client->post(null, [], $this->parameters)->send());
        $this->applyRecoveryRate($action);
        $this->responses[] = $response;
        $this->processNextTokenRequest($action, $response['result'], $response['result_root']);

        return $this->responses;
    }

    /**
     * @param string            $action
     * @param \SimpleXMLElement $parent
     * @param string            $resultRoot
     */
    protected function processNextTokenRequest($action, \SimpleXMLElement $parent, $resultRoot)
    {
        if ((string)$parent->{$resultRoot}->{self::NEXT_TOKEN_PARAM}) {
            $nextToken = (string)$parent->{$resultRoot}->{self::NEXT_TOKEN_PARAM};

            $this->processParameters(
                $action . self::BY_NEXT_TOKEN_SUF,
                null,
                [self::NEXT_TOKEN_PARAM => $nextToken]
            );

            $response          = $this->formatResponse($this->client->post(null, [], $this->parameters)->send());
            $this->responses[] = $response;
            $this->applyRecoveryRate($action);
            $this->processNextTokenRequest($action, $response['result'], $response['result_root']);
        }
    }

    /**
     * @param Response $response
     * @return array
     */
    protected function formatResponse(Response $response)
    {
        $resultRoot = $this->parameters[self::ACTION_PARAM] . self::ACTION_RESULT_SUF;
        $namespace  = $this->client->getBaseUrl() . '/' . $this->authHandler->getVersion();

        return [
            'result'      => $response->xml()->children($namespace),
            'result_root' => $resultRoot
        ];
    }

    /**
     * @return array
     */
    public function getAuthParameters()
    {
        return [
            'SellerId'           => $this->authHandler->getMerchantId(),
            'MarketplaceId.Id.1' => $this->authHandler->getMarketplaceId(),
            'AWSAccessKeyId'     => $this->authHandler->getKeyId(),
            'Timestamp'          => $this->authHandler->getFormattedTimestamp(),
            'Version'            => $this->authHandler->getVersion(),
            'SignatureVersion'   => $this->authHandler->getSignatureVersion(),
            'SignatureMethod'    => $this->authHandler->getSignatureMethod(),
        ];
    }

    /**
     * @param string          $action
     * @param FilterInterface $filter
     * @param array           $parameters
     */
    protected function processParameters($action, FilterInterface $filter = null, array $parameters = [])
    {
        $this->parameters = $this->getAuthParameters();
        if ($filter) {
            $this->parameters = $filter->process($this->parameters);
        }
        $this->parameters[self::ACTION_PARAM] = $action;
        if (isset($parameters[self::NEXT_TOKEN_PARAM])) {
            $this->parameters[self::NEXT_TOKEN_PARAM] = $parameters[self::NEXT_TOKEN_PARAM];
        }
        $signature                     = $this->authHandler->getSignature(
            $this->parameters,
            $this->client->getBaseUrl()
        );
        $this->parameters['Signature'] = $signature;
    }

    /**
     * @param string $action
     */
    protected function applyRecoveryRate($action)
    {
        if (!isset(static::$throttlingParams[$action])) {
            throw new \InvalidArgumentException('Unknown action ' . $action);
        }
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
