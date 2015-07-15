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
namespace OroCRM\Bundle\AmazonBundle\Amazon;

use OroCRM\Bundle\AmazonBundle\Amazon\Api\OrderRestClient;
use OroCRM\Bundle\AmazonBundle\Amazon\Api\AuthorizationHandler;
use OroCRM\Bundle\AmazonBundle\Amazon\Filters\Filter;
use Guzzle\Http\ClientInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

class OrderRestClientImpl extends AbstractRestClientImpl implements OrderRestClient
{
    protected $orders = array();

    /**
     * @param ClientInterface $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->restoreRate = 60;
        $this->maxRequestQuote = 6;

        parent::__construct($client, $authHandler);
    }

    /**
     * @param Filter $filter
     * @return array|mixed
     */
    public function getOrders(Filter $filter)
    {
        $this->action = 'ListOrders';
        $this->orders = array();

        $response = $this->makeRequest($filter);
        $res = $response->xpath('c:ListOrdersResult')[0];

        $this->processOrdersArrayFromResponse($response, 'c:ListOrdersResult/c:Orders/c:Order');
        $this->processTokenResponse($res);

        return $this->orders;
    }

    /**
     * @param string $nextToken
     */
    protected function getOrdersByNextToken($nextToken)
    {
        $this->action = 'ListOrdersByNextToken';
        $this->prepareRequestWithNextToken($nextToken);

        $response = parent::makeRequest();
        $res = $response->xpath('c:ListOrdersByNextTokenResult')[0];

        $this->processOrdersArrayFromResponse($response, 'c:ListOrdersByNextTokenResult/c:Orders/c:Order');
        $this->processTokenResponse($res);
    }

    /**
     * @param string $nextToken
     */
    protected function prepareRequestWithNextToken($nextToken)
    {
        $this->parameters = $this->getParameters();
        $this->parameters['Action'] = $this->action;
        $this->parameters['NextToken'] = $nextToken;
    }

    /**
     * @param SimpleXMLElement $res
     */
    protected function processTokenResponse($res)
    {
        $nextToken = (string) $res->NextToken ? (string) $res->NextToken : null;

        if ($nextToken) {
            $this->getOrdersByNextToken($nextToken);
        }
    }

    /**
     * @param SimpleXMLElement $response
     * @param string $path
     */
    protected function processOrdersArrayFromResponse($response, $path)
    {
        $ordersArray = $response->xpath($path);
        $this->orders = array_merge($this->orders, $ordersArray);
    }
}
