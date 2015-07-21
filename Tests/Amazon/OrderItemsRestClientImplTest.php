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
namespace OroCRM\Bundle\AmazonBundle\Tests\Amazon;

use OroCRM\Bundle\AmazonBundle\Amazon\OrderItemsRestClientImpl;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class OrderItemsRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Guzzle\Http\ClientInterface
     * @Mock Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * @var \OroCRM\Bundle\AmazonBundle\Amazon\Api\AuthorizationHandler
     * @Mock OroCRM\Bundle\AmazonBundle\Amazon\Api\AuthorizationHandler
     */
    private $authHandler;

    /**
     * @var \Guzzle\Http\Message\RequestInterface
     * @Mock Guzzle\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @var \Guzzle\Http\Message\Response
     * @Mock Guzzle\Http\Message\Response
     */
    private $response;

    /**
     * @var \SimpleXmlElement
     */
    private $responseXml;

    /**
     * @var \OroCRM\Bundle\AmazonBundle\Amazon\Filters\Filter
     * @Mock OroCRM\Bundle\AmazonBundle\Amazon\Filters\Filter
     */
    private $filter;

    /**
     * @var array
     */
    private $parsedResponseArray;

    /**
     * @var OrderItemsRestClientImpl
     */
    private $orderItemsRestClient;


    public function setUp()
    {
        MockAnnotations::init($this);

        $this->responseXml = new \SimpleXMLElement('<ListOrderItemsResponse xmlns="https://mws.amazonservices.com/Orders/2013-09-01"><ListOrderItemsResult><OrderItems><OrderItem><OrderItemId>1</OrderItemId></OrderItem><OrderItem><OrderItemId>2</OrderItemId></OrderItem></OrderItems></ListOrderItemsResult></ListOrderItemsResponse>');

        $this->parsedResponseArray = [
            new \SimpleXMLElement('<OrderItem><OrderItemId>1</OrderItemId></OrderItem>'),
            new \SimpleXMLElement('<OrderItem><OrderItemId>2</OrderItemId></OrderItem>')
        ];

        $this->orderItemsRestClient = new OrderItemsRestClientImpl($this->client, $this->authHandler);
    }

    public function testGetOrderItems()
    {
        $parameters = [
            'SellerId'           => 'SellerId',
            'MarketplaceId.Id.1' => 'MarketplaceId.Id.1',
            'Action'             => 'Action',
            'AWSAccessKeyId'     => 'AWSAccessKeyId',
            'Timestamp'          => 'Timestamp',
            'Version'            => 'Version',
            'SignatureVersion'   => 'SignatureVersion',
            'SignatureMethod'    => 'SignatureMethod',
            'Signature'          => 'Signature',
        ];

        $this->authHandler->expects($this->once())
            ->method('getSignature');

        $this->filter->expects($this->once())
            ->method('process')
            ->with($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY))
            ->will($this->returnValue($parameters));

        $this->client->expects($this->once())
            ->method('post')
            ->will($this->returnValue($this->request));

        $this->request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('xml')
            ->will($this->returnValue($this->responseXml));

        $orderItems = $this->orderItemsRestClient->getOrderItems($this->filter);

        $this->assertCount(2, $orderItems);
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[0]->asXml(), $orderItems[0]->asXml());
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[1]->asXml(), $orderItems[1]->asXml());
    }
}
