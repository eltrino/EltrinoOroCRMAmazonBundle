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

namespace Eltrino\OroCrmAmazonBundle\Provider\Transport;

use Eltrino\OroCrmAmazonBundle\Amazon\AmazonRestClientFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;
use Eltrino\OroCrmAmazonBundle\Amazon\Client\Response;
use Eltrino\OroCrmAmazonBundle\Amazon\Client\RestClientFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\OrderIterator;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;

use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\DefaultAuthorizationHandler;

/**
 * Amazon REST transport
 * used to fetch and pull data to/from Amazon instance
 * with sessionId param using REST requests
 *
 * @package Eltrino\OroCrmAmazonBundle
 */
class AmazonRestTransport implements TransportInterface
{
    /** @var RestClient */
    protected $amazonClient;

    /** @var FiltersFactory */
    protected $filtersFactory;

    /** @var array */
    protected $settings = [];

    /** @var DefaultAuthorizationHandler */
    protected $authHandler;

    /** @var AmazonRestClientFactory */
    protected $clientFactory;

    /**
     * @param RestClientFactory $clientFactory
     * @param FiltersFactory          $filtersFactory
     */
    public function __construct(RestClientFactory $clientFactory, FiltersFactory $filtersFactory)
    {
        $this->clientFactory = $clientFactory;
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'eltrino.amazon.transport.rest.label';
    }

    /**
     * @return string
     */
    public function getSettingsFormType()
    {
        return 'eltrino_amazon_rest_transport_setting_form_type';
    }

    /**
     * @return string
     */
    public function getSettingsEntityFQCN()
    {
        return 'Eltrino\OroCrmAmazonBundle\Entity\AmazonRestTransport';
    }

    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
        $settings           = $transportEntity->getSettingsBag();
        $this->amazonClient = $this->clientFactory->create(
            $settings->get('wsdl_url'),
            $settings->get('aws_access_key_id'),
            $settings->get('aws_secret_access_key'),
            $settings->get('aws_merchant_id'),
            $settings->get('aws_marketplace_id')
        );
    }

    /**
     * @param string $action
     * @param array $params
     * @return array|mixed
     * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException
     */
    public function call($action, $params = [])
    {

    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        $response = $this->amazonClient->sendRequest(new Request(RestClient::GET_SERVICE_STATUS));

        return $this->getStatusFromResponse($response);
    }

    /**
     * @param \DateTime $from
     * @return OrderIterator
     */
    public function getModOrders(\DateTime $from)
    {
        $now = $this->getNowDate();
        $filter = $this
            ->filtersFactory
            ->createModTimeRangeFilter($from, $now);

        return $this->getOrders($from, $filter);
    }

    /**
     * @param \DateTime $from
     * @return OrderIterator
     */
    public function getInitialOrders(\DateTime $from)
    {
        $now = $this->getNowDate();
        $filter = $filter = $this
            ->filtersFactory
            ->createCreateTimeRangeFilter($from, $now);

        return $this->getOrders($from, $filter);
    }

    /**
     * @param Response $response
     * @return bool
     */
    protected function getStatusFromResponse(Response $response)
    {
        return (string)$response->getResult()->{$response->getResultRoot()}->Status === RestClient::STATUS_GREEN;
    }

    /**
     * @param \DateTime|null $startSyncDate
     * @param Filter         $filter
     * @return OrderIterator
     */
    protected function getOrders(\DateTime $startSyncDate = null, Filter $filter)
    {
        $compositeFilter = $this->filtersFactory->createCompositeFilter();
        $compositeFilter->addFilter($filter);

        return new OrderIterator($this->amazonClient, $this->filtersFactory, $startSyncDate, $filter);
    }

    /**
     * @return \DateTime
     */
    protected function getNowDate()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        /**
         * Amazon mws api requirement:
         * Must be no later than two minutes before the time that the request was submitted.
         */
        $now->sub(new \DateInterval('PT3M'));

        return $now;
    }
}
