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

use Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\Api\AmazonRestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Api\CheckRestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Api\OrderRestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Api\OrderItemsRestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Api\OrderByNextTokenRestClient;
use Guzzle\Http\ClientInterface;

class AmazonRestClientImpl implements AmazonRestClient
{
    /**
     * @var CheckRestClient
     */
    private $checkRestClient;

    /**
     * @var OrderRestClient
     */
    private $orderRestClient;

    /**
     * @var OrderItemsRestClient
     */
    private $orderItemsRestClient;

    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->checkRestClient            = new CheckRestClientImpl($client, $authHandler);
        $this->orderRestClient            = new OrderRestClientImpl($client, $authHandler);
        $this->orderItemsRestClient       = new OrderItemsRestClientImpl($client, $authHandler);
    }

    /**
     * @return CheckRestClient
     */
    function getCheckRestClient()
    {
        return $this->checkRestClient;
    }

    /**
     * Retrieves Amazon Order Client
     * @return OrderRestClient
     */
    function getOrderRestClient()
    {
        return $this->orderRestClient;
    }

    /**
     * Retrieves Amazon Order Items Client
     * @return OrderItemsRestClient
     */
    function getOrderItemsRestClient()
    {
        return $this->orderItemsRestClient;
    }

}