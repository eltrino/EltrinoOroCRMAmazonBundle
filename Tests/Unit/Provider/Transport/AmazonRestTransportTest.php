<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Unit\Provider\Transport;

use OroCRM\Bundle\AmazonBundle\Client\Filters\CompositeFilter;
use OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;
use OroCRM\Bundle\AmazonBundle\Client\RestClientFactory;
use OroCRM\Bundle\AmazonBundle\Provider\Iterator\OrderIterator;
use OroCRM\Bundle\AmazonBundle\Provider\Transport\AmazonRestTransport;
use OroCRM\Bundle\AmazonBundle\Entity\AmazonRestTransport as EntityAmazonRestTransport;

class AmazonRestTransportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AmazonRestTransport
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RestClientFactory
     */
    protected $clientFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RestClient
     */
    protected $client;

    protected function setUp()
    {
        $this->client = $this
            ->getMockBuilder('OroCRM\Bundle\AmazonBundle\Client\RestClient')
            ->disableOriginalConstructor()
            ->getMock();
        $this->clientFactory = $this
            ->getMockBuilder('OroCRM\Bundle\AmazonBundle\Client\RestClientFactory')
            ->getMock();

        $this->clientFactory
            ->expects($this->any())
            ->method('create')
            ->willReturn($this->client);

        $this->filtersFactory = $this
            ->getMockBuilder('OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory')
            ->getMock();

        $this->object = new AmazonRestTransport($this->clientFactory, $this->filtersFactory);
        $transportEntity = new EntityAmazonRestTransport();
        $this->object->init($transportEntity);
    }

    public function testGetLabel()
    {
        $this->assertEquals('orocrm.amazon.transport.rest.label', $this->object->getLabel());
    }

    public function testGetSettingsFormType()
    {
        $this->assertEquals(
            'orocrm_amazon_rest_transport_setting_form_type',
            $this->object->getSettingsFormType()
        );
    }

    public function testGetSettingsEntityFQCN()
    {
        $this->assertEquals(
            'OroCRM\Bundle\AmazonBundle\Entity\AmazonRestTransport',
            $this->object->getSettingsEntityFQCN()
        );
    }

    public function testGetStatus()
    {
        $compositeFilter = new CompositeFilter();

        $this->filtersFactory
            ->expects($this->once())
            ->method('createCompositeFilter')
            ->willReturn($compositeFilter);


        $result = new \stdClass;
        $result->GetServiceStatusResult = new \StdClass;
        $result->GetServiceStatusResult->Status = 'GREEN';
        $this->client
            ->expects($this->once())
            ->method('makeRequest')
            ->with(RestClient::GET_SERVICE_STATUS_ACTION, $compositeFilter)
            ->willReturn([['result' => $result, 'result_root' => 'GetServiceStatusResult']]);

        $this->assertTrue($this->object->getStatus());
    }

    public function testGetModOrders()
    {
        $from = new \DateTime();
        $orderIterator = new OrderIterator($this->client, $this->filtersFactory, $from, OrderIterator::MODIFIED_MODE);
        $this->assertEquals($orderIterator, $this->object->getModOrders($from));
    }

    public function testGetInitialOrders()
    {
        $from = new \DateTime();
        $orderIterator = new OrderIterator($this->client, $this->filtersFactory, $from, OrderIterator::INITIAL_MODE);
        $this->assertEquals($orderIterator, $this->object->getInitialOrders($from));
    }
}
