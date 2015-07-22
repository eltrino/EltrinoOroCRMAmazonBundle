<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Unit\Provider\Iterator;

use OroCRM\Bundle\AmazonBundle\Client\Filters\CompositeFilter;
use OroCRM\Bundle\AmazonBundle\Client\Filters\CreateTimeRangeFilter;
use OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;
use OroCRM\Bundle\AmazonBundle\Provider\Iterator\OrderIterator;

class OrderIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrderIterator
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RestClient
     */
    protected $client;

    /**
     * @var \DateTime
     */
    protected $from;

    protected function setUp()
    {
        $this->from = new \DateTime();
        $this->filtersFactory = $this
            ->getMockBuilder('OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory')
            ->getMock();
        $this->client = $this
            ->getMockBuilder('OroCRM\Bundle\AmazonBundle\Client\RestClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new OrderIterator(
            $this->client,
            $this->filtersFactory,
            $this->from,
            OrderIterator::INITIAL_MODE
        );
    }

    public function testIteration()
    {
        $this->filtersFactory
            ->expects($this->exactly(2))
            ->method('createCompositeFilter')
            ->willReturn(new CompositeFilter());
        $this->filtersFactory
            ->expects($this->once())
            ->method('createCreateTimeRangeFilter')
            ->willReturn(new CreateTimeRangeFilter(new \DateTime(), new \DateTime()));

        $amazonOrderId = 123456789;

        $this->filtersFactory
            ->expects($this->any())
            ->method('createAmazonOrderIdFilter')
            ->willReturn($amazonOrderId);

        $xml = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../Fixtures/OrdersResult.xml'));

        $this->client
            ->expects($this->once())
            ->method('makeRequest')
            ->willReturn([[
                'result' => $xml->children(),
                'result_root' => 'ListOrdersResult'
            ]]);
        $this->assertEquals(
            [$xml->children()->ListOrdersResult->Orders->children()],
            iterator_to_array($this->object)
        );
    }
}
