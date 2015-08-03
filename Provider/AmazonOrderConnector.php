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

namespace Eltrino\OroCrmAmazonBundle\Provider;

use Eltrino\OroCrmAmazonBundle\Provider\Transport\AmazonRestTransport;

use Oro\Bundle\ImportExportBundle\Reader\IteratorBasedReader;
use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\IntegrationBundle\Entity\Status;

class AmazonOrderConnector extends AbstractConnector
{
    const ORDER_TYPE = 'Eltrino\OroCrmAmazonBundle\Entity\Order';
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
