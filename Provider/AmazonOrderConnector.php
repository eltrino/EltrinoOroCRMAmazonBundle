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

use Eltrino\OroCrmAmazonBundle\Amazon\AmazonRestClientFactory;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\AmazonDataIterator;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\Order\InitialModeLoader;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\Order\UpdateModeLoader;
use Oro\Bundle\ImportExportBundle\Reader\IteratorBasedReader;

use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Entity\Status;

class AmazonOrderConnector extends IteratorBasedReader implements ConnectorInterface
{
    const ORDER_TYPE = 'Eltrino\OroCrmAmazonBundle\Entity\Order';

    /**
     * @var ContextRegistry
     */
    protected $contextRegistry;

    /**
     * @var ConnectorContextMediator
     */
    protected $contextMediator;

    /**
     * @var AmazonRestClientFactory
     */
    private $amazonRestClientFactory;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory
     */
    private $filtersFactory;

    /**
     * @param ContextRegistry $contextRegistry
     * @param ConnectorContextMediator $contextMediator
     * @param AmazonRestClientFactory $amazonRestClientFactory
     * @param FiltersFactory $filtersFactory
     */
    public function __construct(ContextRegistry $contextRegistry,
                                ConnectorContextMediator $contextMediator, AmazonRestClientFactory $amazonRestClientFactory,
                                FiltersFactory $filtersFactory)
    {
        $this->contextRegistry = $contextRegistry;
        $this->contextMediator = $contextMediator;
        $this->amazonRestClientFactory = $amazonRestClientFactory;
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Order connector';
    }

    /**
     * @param ContextInterface $context
     */
    protected function initializeFromContext(ContextInterface $context)
    {
        $channel = $this->contextMediator->getChannel($context);
        $settings = $channel->getTransport()->getSettingsBag();

        $amazonRestClient = $this->initializeAmazonRestClient($settings);

        /** @var Status $status */
        $status = $channel
            ->getStatusesForConnector($this->getType(), Status::STATUS_COMPLETED)
            ->first();

        $loader = null;
        if (false !== $status) { // update_mode
            $loader = new UpdateModeLoader($amazonRestClient, $this->filtersFactory, $status->getDate());
        } else { // initial_mode
            $loader = new InitialModeLoader($amazonRestClient, $this->filtersFactory, $settings->get('start_sync_date'));
        }
        $orderIterator = new AmazonDataIterator($loader);
        $this->setSourceIterator($orderIterator);
    }

    private function initializeAmazonRestClient(ParameterBag $settings)
    {
        $amazonRestClient = $this->amazonRestClientFactory->create(
            $settings->get('wsdl_url'),
            $settings->get('aws_access_key_id'),
            $settings->get('aws_secret_access_key'),
            $settings->get('merchant_id'),
            $settings->get('marketplace_id')
        );

        return $amazonRestClient;
    }

    /**
     * Returns entity name that will be used for matching "import processor"
     *
     * @return string
     */
    public function getImportEntityFQCN()
    {
        return self::ORDER_TYPE;
    }

    /**
     * Returns job name for import
     *
     * @return string
     */
    public function getImportJobName()
    {
        return 'amazon_order_import';
    }

    /**
     * Returns type name, the same as registered in service tag
     *
     * @return string
     */
    public function getType()
    {
        return 'order';
    }
}
