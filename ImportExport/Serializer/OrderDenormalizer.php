<?php

namespace OroCRM\Bundle\AmazonBundle\ImportExport\Serializer;

use Doctrine\ORM\EntityManager;

use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\DenormalizerInterface;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Entity\Repository\ChannelRepository;
use OroCRM\Bundle\AmazonBundle\Entity\Order;
use OroCRM\Bundle\AmazonBundle\Model\Order\OrderFactory;
use OroCRM\Bundle\AmazonBundle\Provider\Connector\OrderConnector;

class OrderDenormalizer implements DenormalizerInterface
{
    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var  ChannelRepository
     */
    protected $channelRepository;

    /**
     * @param EntityManager $em
     * @param OrderFactory  $orderFactory
     */
    public function __construct(EntityManager $em, OrderFactory $orderFactory)
    {
        $this->channelRepository = $em->getRepository('OroIntegrationBundle:Channel');
        $this->orderFactory      = $orderFactory;
    }

    /**
     * @param mixed  $data
     * @param string $type
     * @param null   $format
     * @param array  $context
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return is_object($data) && ($type == OrderConnector::ORDER_TYPE);
    }

    /**
     * @param mixed  $data
     * @param string $class
     * @param null   $format
     * @param array  $context
     * @return object|Order
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $channel = $this->getChannelFromContext($context);
        $order   = $this->orderFactory->createOrder($data);
        $order->setChannel($channel);

        return $order;
    }

    /**
     * @param array $context
     *
     * @return Channel
     * @throws \LogicException
     */
    protected function getChannelFromContext(array $context)
    {
        if (!isset($context['channel'])) {
            throw new \LogicException('Context should contain reference to channel');
        }

        return $this->channelRepository->getOrLoadById($context['channel']);
    }
}
