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
use Eltrino\OroCrmAmazonBundle\Amazon\Client\RestClientFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\CreateTimeRangeFilter;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\ModTimeRangeFilter;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Eltrino\OroCrmAmazonBundle\Provider\Transport\AmazonRestTransport;
use Eltrino\OroCrmAmazonBundle\Entity\AmazonRestTransport as TransportEntity;

class AmazonRestTransportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|RestClientFactory
     */
    protected $clientFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|RestClient
     */
    protected $client;

    /**
     * @var AmazonRestTransport
     */
    protected $object;

    protected function setUp()
    {
        $this->client = $this
            ->getMockBuilder('Eltrino\OroCrmAmazonBundle\Amazon\RestClient')
            ->disableOriginalConstructor()
            ->getMock();
        $this->clientFactory = $this
            ->getMockBuilder('Eltrino\OroCrmAmazonBundle\Amazon\Client\RestClientFactory')
            ->getMock();
        $this->clientFactory
            ->expects($this->any())
            ->method('create')
            ->willReturn($this->client);
        $this->filtersFactory = $this
            ->getMockBuilder('Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory')
            ->getMock();

        $this->object = new AmazonRestTransport($this->clientFactory, $this->filtersFactory);
        $date   = new \DateTime('now', new \DateTimeZone('UTC'));
        $entity = new TransportEntity();
        $entity
            ->setKeyId('keyId')
            ->setSecret('secret')
            ->setMerchantId('merchantId')
            ->setMarketplaceId('marketplaceId')
            ->setSyncStartDate($date)
            ->setWsdlUrl('wsdlUrl');

        $this->object->init($entity);
    }

    public function testGetOrders()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $from = clone $now;
        $from->sub(new \DateInterval('PT3M'));
        $iteratorClass = 'Eltrino\OroCrmAmazonBundle\Provider\Iterator\AmazonDataIterator';
        $this->filtersFactory
            ->expects($this->at(0))
            ->method('createCreateTimeRangeFilter')
            ->willReturn(new CreateTimeRangeFilter($from, $now));
        $this->filtersFactory
            ->expects($this->at(1))
            ->method('createModTimeRangeFilter')
            ->willReturn(new ModTimeRangeFilter($from, $now));
        $this->assertInstanceOf(
            $iteratorClass,
            $this->object->getInitialOrders($from)
        );
        $this->assertInstanceOf(
            $iteratorClass,
            $this->object->getModOrders($from)
        );
    }

    public function testGetStatus()
    {
        $xml = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../../Fixtures/GetServiceStatus.xml'));
        $response = $this
            ->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response
            ->expects($this->once())
            ->method('xml')
            ->willReturn($xml);

        $this->client
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $reflectionClass = new ReflectionClass($this->object);
        $namespaceProp = $reflectionClass->getProperty('namespace');
        $namespaceProp->setAccessible(true);
        $namespaceProp->setValue($this->object, 'https://mws.amazonservices.com/Orders/2013-09-01');

        $this->assertTrue($this->object->getStatus());
    }

    public function testGetLabel()
    {
        $this->assertEquals(
            'eltrino.amazon.transport.rest.label',
            $this->object->getLabel()
        );
    }

    public function testGetSettingsEntityFQCN ()
    {
        $this->assertEquals(
            'Eltrino\OroCrmAmazonBundle\Entity\AmazonRestTransport',
            $this->object->getSettingsEntityFQCN()
        );
    }

    public function testGetSettingsFormType()
    {
        $this->assertEquals(
            'eltrino_amazon_rest_transport_setting_form_type',
            $this->object->getSettingsFormType()
        );
    }
}
