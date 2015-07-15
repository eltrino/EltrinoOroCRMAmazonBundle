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
use OroCRM\Bundle\AmazonBundle\Amazon\Api\AmazonRestClient;
use OroCRM\Bundle\AmazonBundle\Amazon\Api\CheckRestClient;
use OroCRM\Bundle\AmazonBundle\Amazon\Api\OrderRestClient;
use OroCRM\Bundle\AmazonBundle\Amazon\Api\OrderItemsRestClient;
use OroCRM\Bundle\AmazonBundle\Amazon\Api\OrderByNextTokenRestClient;
use Guzzle\Http\ClientInterface;

class AmazonRestClientImpl implements AmazonRestClient
{
    /**
     * @var CheckRestClient
     */
    protected $checkRestClient;

    /**
     * @var OrderRestClient
     */
    protected $orderRestClient;

    /**
     * @var OrderItemsRestClient
     */
    protected $orderItemsRestClient;

    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->checkRestClient            = new CheckRestClientImpl($client, $authHandler);
        $this->orderRestClient            = new OrderRestClientImpl($client, $authHandler);
        $this->orderItemsRestClient       = new OrderItemsRestClientImpl($client, $authHandler);
    }

    /**
     * @return CheckRestClient
     */
    public function getCheckRestClient()
    {
        return $this->checkRestClient;
    }

    /**
     * Retrieves Amazon Order Client
     * @return OrderRestClient
     */
    public function getOrderRestClient()
    {
        return $this->orderRestClient;
    }

    /**
     * Retrieves Amazon Order Items Client
     * @return OrderItemsRestClient
     */
    public function getOrderItemsRestClient()
    {
        return $this->orderItemsRestClient;
    }
}
