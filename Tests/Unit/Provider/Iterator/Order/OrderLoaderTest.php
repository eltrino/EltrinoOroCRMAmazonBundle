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
namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\Provider\Iterator\Order;


use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\Order\OrderLoader;
use Guzzle\Http\Message\Response;

class OrderLoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|RestClient */
    protected $client;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Filter */
    protected $firstFilter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Response */
    protected $response;

    /** @var OrderLoader */
    protected $object;

    protected function setUp()
    {
        $this->client   = $this
            ->getMockBuilder('Eltrino\OroCrmAmazonBundle\Amazon\RestClient')
            ->disableOriginalConstructor()
            ->getMock();
        $this->response = $this
            ->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $this->client
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn($this->response);

        $this->firstFilter = $this
            ->getMockBuilder('Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter')
            ->getMock();

        $this->object = new OrderLoader($this->client, $this->firstFilter, '');
    }

    public function testGetNextToken()
    {
        $this->assertNull($this->object->getNextToken());
    }

    public function testIsFirstRequestSend()
    {
        $this->assertFalse($this->object->isFirstRequestSend());
        $ordersXml = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../../Fixtures/ListOrders.xml'));
        $empty     = new \SimpleXMLElement('<el></el>');
        $this->response
            ->expects($this->at(0))
            ->method('xml')
            ->willReturn($ordersXml);
        $this->response
            ->expects($this->at(1))
            ->method('xml')
            ->willReturn($empty);
        $this->response
            ->expects($this->at(2))
            ->method('xml')
            ->willReturn($empty);
        $this->object->load(2);

        $this->assertTrue($this->object->isFirstRequestSend());
    }

    public function testLoad()
    {
        $ordersXml     = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../../Fixtures/ListOrders.xml'));
        $ordersByNTXml = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../../Fixtures/ListOrdersByNextToken.xml'));
        $empty         = new \SimpleXMLElement('<el></el>');

        $this->response
            ->expects($this->at(0))
            ->method('xml')
            ->willReturn($ordersXml);
        $this->response
            ->expects($this->at(1))
            ->method('xml')
            ->willReturn($empty);
        $this->response
            ->expects($this->at(2))
            ->method('xml')
            ->willReturn($empty);
        $this->response
            ->expects($this->at(3))
            ->method('xml')
            ->willReturn($ordersByNTXml);
        $this->response
            ->expects($this->at(4))
            ->method('xml')
            ->willReturn($empty);
        $this->response
            ->expects($this->at(5))
            ->method('xml')
            ->willReturn($empty);

        $orders = [];
        foreach ($ordersXml->children()->ListOrdersResult->Orders->children() as $order) {
            $orders[] = $order;
        }
        $ordersNT = [];
        foreach ($ordersByNTXml->children()->ListOrdersByNextTokenResult->Orders->children() as $order) {
            $ordersNT[] = $order;
        }
        $this->assertEquals($orders, $this->object->load(2));
        $this->assertNotNull($this->object->getNextToken());
        $this->assertEquals($ordersNT, $this->object->load(2));
        $this->assertNull($this->object->getNextToken());
        $this->assertEquals([], $this->object->load(2));
    }
}
