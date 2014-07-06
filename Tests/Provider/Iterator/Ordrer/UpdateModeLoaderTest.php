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
namespace Eltrino\OroCrmAmazonBundle\Tests\Provider\Iterator\Order;

use Eltrino\OroCrmAmazonBundle\Provider\Iterator\Order\UpdateModeLoader;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class UpdateModeLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Api\AmazonRestClient
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Api\AmazonRestClient
     */
    private $amazonRestClient;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Api\OrderRestClient
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Api\OrderRestClient
     */
    private $amazonOrderClient;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Api\OrderItemsRestClient
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Api\OrderItemsRestClient
     */
    private $amazonOrderItemsClient;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory
     */
    private $filtersFactory;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Filters\CompositeFilter
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Filters\CompositeFilter
     */
    private $compositeFilter;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Filters\ModTimeRangeFilter
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Filters\ModTimeRangeFilter
     */
    private $modTimeRangeFilter;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Filters\AmazonOrderIdFilter
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Filters\AmazonOrderIdFilter
     */
    private $amazonOrderIdFilter;

    /**
     * @var UpdateModeLoader
     */
    private $loader;

    protected function setUp()
    {
        MockAnnotations::init($this);

        $elements = [
            new \SimpleXMLElement('<Order><AmazonOrderId>1</AmazonOrderId></Order>'),
            new \SimpleXMLElement('<Order><AmazonOrderId>2</AmazonOrderId></Order>')
        ];

        $this->amazonRestClient
            ->expects($this->any())
            ->method('getOrderRestClient')
            ->will($this->returnValue($this->amazonOrderClient));

        $this->amazonOrderClient
            ->expects($this->at(0))
            ->method('getOrders')
            ->with($this->equalTo($this->compositeFilter))
            ->will($this->returnValue($elements));

        $this->amazonOrderClient
            ->expects($this->at(1))
            ->method('getOrders')
            ->with($this->equalTo($this->compositeFilter))
            ->will($this->returnValue(array()));

        $this->filtersFactory
            ->expects($this->once())
            ->method('createCompositeFilter')
            ->will($this->returnValue($this->compositeFilter));

        $this->filtersFactory
            ->expects($this->exactly(2))
            ->method('createModTimeRangeFilter')
            ->will($this->returnValue($this->modTimeRangeFilter));

        $startSycDate = new \DateTime('now');
        $startSycDate->sub(new \DateInterval('P3D')); // Create Time Range has 5 days interval. As a result loader should try to load 2 times with two dates interval

        $this->loader = new UpdateModeLoader($this->amazonRestClient, $this->filtersFactory, $startSycDate);
    }

    public function testLoad()
    {
        $this->filtersFactory
            ->expects($this->exactly(2))
            ->method('createAmazonOrderIdFilter')
            ->will($this->returnValue($this->amazonOrderIdFilter));

        $this->amazonRestClient
            ->expects($this->exactly(2))
            ->method('getOrderItemsRestClient')
            ->will($this->returnValue($this->amazonOrderItemsClient));

        $this->amazonOrderItemsClient
            ->expects($this->exactly(2))
            ->method('getOrderItems')
            ->will($this->returnValue(array()));

        $elements = $this->loader->load();
        $this->assertNotEmpty($elements);
        $this->assertCount(2, $elements);
        $this->assertEquals($elements[0], new \SimpleXMLElement('<Order><AmazonOrderId>1</AmazonOrderId></Order>'));

        $elements = $this->loader->load();
        $this->assertEmpty($elements);
    }
}
