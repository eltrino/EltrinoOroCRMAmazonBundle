<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Unit\ImportExport\Serializer;

use OroCRM\Bundle\AmazonBundle\ImportExport\Serializer\OrderDenormalizer;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;
use OroCRM\Bundle\AmazonBundle\Provider\Connector\OrderConnector;

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
        $this->assertTrue($orderDenormilizer->supportsDenormalization($testObject, OrderConnector::ORDER_TYPE));
    }
}
