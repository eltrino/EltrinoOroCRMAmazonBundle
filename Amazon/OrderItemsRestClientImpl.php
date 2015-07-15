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

use OroCRM\Bundle\AmazonBundle\Amazon\Api\AuthorizationHandler;
use OroCRM\Bundle\AmazonBundle\Amazon\Api\OrderItemsRestClient;
use OroCRM\Bundle\AmazonBundle\Amazon\Filters\Filter;
use Guzzle\Http\ClientInterface;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

class OrderItemsRestClientImpl extends AbstractRestClientImpl implements OrderItemsRestClient
{
    /**
     * @param ClientInterface $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->restoreRate = 2;
        $this->maxRequestQuote = 30;
        $this->action = 'ListOrderItems';
        parent::__construct($client, $authHandler);
    }

    /**
     * @param Filter $filter
     * @return mixed
     */
    public function getOrderItems(Filter $filter)
    {
        $response = $this->makeRequest($filter);
        return $this->processOrderItemsArrayFromResponse($response, 'c:ListOrderItemsResult/c:OrderItems/c:OrderItem');
    }

    /**
     * @param $response
     * @param $path
     * @return mixed
     */
    protected function processOrderItemsArrayFromResponse($response, $path)
    {
        $orderItemsArray = $response->xpath('c:ListOrderItemsResult/c:OrderItems/c:OrderItem');

        return $orderItemsArray;
    }
}
