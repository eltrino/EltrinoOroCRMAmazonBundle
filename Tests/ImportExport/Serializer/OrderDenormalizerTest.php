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
namespace OroCRM\Bundle\AmazonBundle\Tests\ImportExport\Serializer;

use OroCRM\Bundle\AmazonBundle\ImportExport\Serializer\OrderDenormalizer;
use OroCRM\Bundle\AmazonBundle\Provider\AmazonOrderConnector;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class OrderDenormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \OroCRM\Bundle\AmazonBundle\Model\Order\OrderFactory
     * @Mock OroCRM\Bundle\AmazonBundle\Model\Order\OrderFactory
     */
    private $orderFactory;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Entity\Repository\ChannelRepository
     * @Mock Oro\Bundle\IntegrationBundle\Entity\Repository\ChannelRepository
     */
    private $channelRepository;

    /**
     * @var \Doctrine\ORM\EntityManager
     * @Mock Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Entity\Channel
     * @Mock Oro\Bundle\IntegrationBundle\Entity\Channel
     */
    private $channel;

    /**
     * @var \OroCRM\Bundle\AmazonBundle\Entity\Order
     * @Mock OroCRM\Bundle\AmazonBundle\Entity\Order
     */
    private $order;

    public function setUp()
    {
        MockAnnotations::init($this);
    }

    public function testDenormalize()
    {
        $data    = new \SimpleXMLElement('<Order><AmazonOrderId>1</AmazonOrderId></Order>');
        $class   = '';
        $context = [
            'channel' => '100'
        ];

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with('OroIntegrationBundle:Channel')
            ->will($this->returnValue($this->channelRepository));

        $this->channelRepository->expects($this->once())
            ->method('getOrLoadById')
            ->with($context['channel'])
            ->will($this->returnValue($this->channel));

        $this->orderFactory
            ->expects($this->once())
            ->method('createOrder')
            ->with($this->equalTo($data))
            ->will($this->returnValue($this->order));

        $this->order
            ->expects($this->once())
            ->method('setChannel')
            ->with($this->equalTo($this->channel))
            ->will($this->returnValue($this->order));

        $orderDenormilizer = new OrderDenormalizer($this->em, $this->orderFactory);
        $orderDenormilizer->denormalize($data, $class, null, $context);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Context should contain reference to channel
     */
    public function testDenormalizeWithoutChannel()
    {
        $data    = new \SimpleXMLElement('<Order><AmazonOrderId>1</AmazonOrderId></Order>');
        $class   = '';
        $context = [];

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('OroIntegrationBundle:Channel'))
            ->will($this->returnValue($this->channelRepository));

        $orderDenormilizer = new OrderDenormalizer($this->em, $this->orderFactory);
        $orderDenormilizer->denormalize($data, $class, null, $context);
    }

    public function testSupportsDenormalization()
    {
        $testObject = new \SimpleXMLElement('<Order><AmazonOrderId>1</AmazonOrderId></Order>');

        $orderDenormilizer = new OrderDenormalizer($this->em, $this->orderFactory);
        $this->assertFalse($orderDenormilizer->supportsDenormalization([], 'TEST_TYPE'));
        $this->assertFalse($orderDenormilizer->supportsDenormalization('string', 'TEST_TYPE'));
        $this->assertTrue($orderDenormilizer->supportsDenormalization($testObject, AmazonOrderConnector::ORDER_TYPE));
    }

}
