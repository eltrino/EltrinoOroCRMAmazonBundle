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
namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\Amazon;

use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;
use Eltrino\OroCrmAmazonBundle\Amazon\DefaultAuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Guzzle\Http\ClientInterface;

class RestClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var RestClient */
    protected $object;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface */
    protected $client;

    /** @var \PHPUnit_Framework_MockObject_MockObject|DefaultAuthorizationHandler */
    protected $authHandler;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Guzzle\Http\ClientInterface')->getMock();
        $this->authHandler = $this->getMockBuilder('Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler')->getMock();
        $this->object = new RestClient($this->client, $this->authHandler);
    }

    public function testSendRequest()
    {
        $listAction = RestClient::LIST_ORDER_ITEMS;
        $byNextTokenAction = RestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN;

        $reflection = new \ReflectionClass($this->object);
        $requestsCounters = $reflection->getProperty('requestsCounters');
        $requestsCounters->setAccessible(true);

        $listRequest = new Request($listAction);
        $byNextTokenRequest = new Request($byNextTokenAction);

        $request = $this->getMockBuilder('Guzzle\Http\Message\EntityEnclosingRequestInterface')
            ->getMock();
        $request
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn('test');
        $this->client
            ->expects($this->any())
            ->method('post')
            ->willReturn($request);

        $this->assertEquals(
            'test',
            $this->object->sendRequest($listRequest)
        );
        $this->assertEquals(
            'test',
            $this->object->sendRequest($byNextTokenRequest)
        );

        $this->assertEquals(2, $requestsCounters->getValue($this->object)[$listAction]);
    }

    public function testGetVersion()
    {
        $this->assertEquals(RestClient::SERVICE_VERSION, $this->object->getVersion());
    }
}
