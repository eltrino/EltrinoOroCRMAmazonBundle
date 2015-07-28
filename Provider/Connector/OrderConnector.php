<?php

namespace Eltrino\OroCrmAmazonBundle\Provider\Connector;

use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;
use Oro\Bundle\IntegrationBundle\Entity\Status;

use Eltrino\OroCrmAmazonBundle\Provider\Transport\AmazonRestTransport;

class OrderConnector extends AbstractConnector
{
    const ORDER_TYPE = 'OroCRM\Bundle\AmazonBundle\Entity\Order';
    const TYPE       = 'order';

    /** @var AmazonRestTransport */
    protected $transport;

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Order connector';
    }

    /**
     * {@inheritdoc}
     */
    public function getImportEntityFQCN()
    {
        return self::ORDER_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getImportJobName()
    {
        return 'amazon_order_import';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConnectorSource()
    {
        $settings = $this->channel->getTransport()->getSettingsBag();
        /** @var Status $status */
        $status = $this->channel
            ->getStatusesForConnector($this->getType(), Status::STATUS_COMPLETED)
            ->first();
        if (false !== $status) {
            return $this->transport->getModOrders($status->getDate());
        } else {
            return $this->transport->getInitialOrders($settings->get('start_sync_date'));
        }
    }
}
