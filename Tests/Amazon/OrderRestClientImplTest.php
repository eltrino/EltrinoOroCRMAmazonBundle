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

use OroCRM\Bundle\AmazonBundle\Amazon\OrderRestClientImpl;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class OrderRestClientImplTest extends \PHPUnit_Framework_TestCase
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
    private $getOrdersResponseWithoutNextToken;

    /**
     * @var \SimpleXmlElement
     */
    private $getOrdersResponseWithNextToken;

    /**
     * @var \SimpleXmlElement
     */
    private $getOrdersByNextToken;

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
     * @var OrderRestClientImpl
     */
    private $orderRestClient;


    public function setUp()
    {
        MockAnnotations::init($this);

        $this->getOrdersResponseWithoutNextToken = new \SimpleXMLElement('<ListOrdersResponse xmlns="https://mws.amazonservices.com/Orders/2013-09-01"><ListOrdersResult><Orders><Order><AmazonOrderId>1</AmazonOrderId></Order><Order><AmazonOrderId>2</AmazonOrderId></Order></Orders></ListOrdersResult></ListOrdersResponse>');
        $this->getOrdersResponseWithNextToken = new \SimpleXMLElement('<ListOrdersResponse xmlns="https://mws.amazonservices.com/Orders/2013-09-01"><ListOrdersResult><NextToken>nextToken</NextToken>><Orders><Order><AmazonOrderId>1</AmazonOrderId></Order><Order><AmazonOrderId>2</AmazonOrderId></Order></Orders></ListOrdersResult></ListOrdersResponse>');

        $this->getOrdersByNextToken = new \SimpleXMLElement('<ListOrdersByNextTokenResponse xmlns="https://mws.amazonservices.com/Orders/2013-09-01"><ListOrdersByNextTokenResult><Orders><Order><AmazonOrderId>1</AmazonOrderId></Order><Order><AmazonOrderId>2</AmazonOrderId></Order></Orders></ListOrdersByNextTokenResult></ListOrdersByNextTokenResponse>');

        $this->parsedResponseArray = [
            new \SimpleXMLElement('<Order><AmazonOrderId>1</AmazonOrderId></Order>'),
            new \SimpleXMLElement('<Order><AmazonOrderId>2</AmazonOrderId></Order>')
        ];

        $this->orderRestClient = new OrderRestClientImpl($this->client, $this->authHandler);
    }

    public function testGetOrdersWithoutNextToken()
    {
        $parameters = array
        (
            'SellerId'           => 'SellerId',
            'MarketplaceId.Id.1' => 'MarketplaceId.Id.1',
            'Action'             => 'Action',
            'AWSAccessKeyId'     => 'AWSAccessKeyId',
            'Timestamp'          => 'Timestamp',
            'Version'            => 'Version',
            'SignatureVersion'   => 'SignatureVersion',
            'SignatureMethod'    => 'SignatureMethod',
            'Signature'          => 'Signature',
        );

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
            ->will($this->returnValue($this->getOrdersResponseWithoutNextToken));

        $orders = $this->orderRestClient->getOrders($this->filter);

        $this->assertCount(2, $orders);
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[0]->asXml(), $orders[0]->asXml());
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[1]->asXml(), $orders[1]->asXml());
    }

    public function testGetOrdersWithNextToken()
    {
        $parameters = array
        (
            'SellerId'           => 'SellerId',
            'MarketplaceId.Id.1' => 'MarketplaceId.Id.1',
            'Action'             => 'Action',
            'AWSAccessKeyId'     => 'AWSAccessKeyId',
            'Timestamp'          => 'Timestamp',
            'Version'            => 'Version',
            'SignatureVersion'   => 'SignatureVersion',
            'SignatureMethod'    => 'SignatureMethod',
            'Signature'          => 'Signature',
        );

        $this->authHandler->expects($this->exactly(2))
            ->method('getSignature');

        $this->filter->expects($this->once())
            ->method('process')
            ->with($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY))
            ->will($this->returnValue($parameters));

        $this->client->expects($this->exactly(2))
            ->method('post')
            ->will($this->returnValue($this->request));

        $this->request->expects($this->exactly(2))
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->response
            ->expects($this->at(0))
            ->method('xml')
            ->will($this->returnValue($this->getOrdersResponseWithNextToken));

        $this->response
            ->expects($this->at(1))
            ->method('xml')
            ->will($this->returnValue($this->getOrdersByNextToken));

        $orders = $this->orderRestClient->getOrders($this->filter);

        $this->assertCount(4, $orders);
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[0]->asXml(), $orders[0]->asXml());
        $this->assertXmlStringEqualsXmlString($this->parsedResponseArray[1]->asXml(), $orders[1]->asXml());
    }
}
