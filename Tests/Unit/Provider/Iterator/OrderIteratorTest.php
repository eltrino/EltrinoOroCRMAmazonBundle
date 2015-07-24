<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Unit\Provider\Iterator;

use OroCRM\Bundle\AmazonBundle\Client\Filters\AmazonOrderIdFilter;
use OroCRM\Bundle\AmazonBundle\Client\Filters\CompositeFilter;
use OroCRM\Bundle\AmazonBundle\Client\Filters\CreateTimeRangeFilter;
use OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;
use OroCRM\Bundle\AmazonBundle\Provider\Iterator\OrderIterator;
use Psr\Log\LoggerInterface;

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
        /** @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface $logger */
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $this->object->setLogger($logger);

        $amazonOrderId = 123456789;
        $compositeFilter = new CompositeFilter();
        $timeFilter = new CreateTimeRangeFilter(new \DateTime(), new \DateTime());
        $amazonOrderIdFilter = new AmazonOrderIdFilter($amazonOrderId);
        $compositeFilter->addFilter($timeFilter);
        $this->filtersFactory
            ->expects($this->exactly(2))
            ->method('createCompositeFilter')
            ->willReturn($compositeFilter);

        $this->filtersFactory
            ->expects($this->once())
            ->method('createCreateTimeRangeFilter')
            ->willReturn($timeFilter);

        $this->filtersFactory
            ->expects($this->any())
            ->method('createAmazonOrderIdFilter')
            ->willReturn($amazonOrderIdFilter);

        $xml = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../Fixtures/OrdersResult.xml'));

        $this->client
            ->expects($this->at(0))
            ->method('requestAction')
            ->with('ListOrders', $compositeFilter)
            ->willReturn([['result' => $xml->children(), 'result_root' => 'ListOrdersResult']]);
        $this->client
            ->expects($this->at(1))
            ->method('requestAction')
            ->with('ListOrderItems', $amazonOrderIdFilter)
            ->willReturn([['result' => [], 'result_root' => 'ListOrderItemsResult']]);

        $this->assertEquals(
            [$xml->children()->ListOrdersResult->Orders->children()],
            iterator_to_array($this->object)
        );
    }
}
