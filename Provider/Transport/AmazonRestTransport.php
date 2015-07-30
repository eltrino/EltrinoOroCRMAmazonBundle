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

use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;
use Eltrino\OroCrmAmazonBundle\Amazon\Client\RestClientFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\AmazonDataIterator;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\Order\OrderLoader;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory;

use Guzzle\Http\Message\Response;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;

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

    /** @var RestClientFactory */
    protected $clientFactory;

    /** @var string */
    protected $namespace;

    /**
     * @param RestClientFactory $clientFactory
     * @param FiltersFactory    $filtersFactory
     */
    public function __construct(RestClientFactory $clientFactory, FiltersFactory $filtersFactory)
    {
        $this->clientFactory  = $clientFactory;
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'eltrino.amazon.transport.rest.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsFormType()
    {
        return 'eltrino_amazon_rest_transport_setting_form_type';
    }

    /**
     * {@inheritdoc}
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
        $baseUrl            = $settings->get('wsdl_url');
        $this->amazonClient = $this->clientFactory->create(
            $baseUrl,
            $settings->get('aws_access_key_id'),
            $settings->get('aws_secret_access_key'),
            $settings->get('merchant_id'),
            $settings->get('marketplace_id')
        );
        $this->namespace    = $baseUrl . '/' . $this->amazonClient->getVersion();
    }

    /**
     * @param string $action
     * @param array  $params
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
     * @return AmazonDataIterator
     */
    public function getModOrders(\DateTime $from)
    {
        $now    = $this->getNowDate();
        $from   = $this->validateFrom($from, $now);
        $filter = $this
            ->filtersFactory
            ->createModTimeRangeFilter($from, $now);

        return $this->getOrders($filter);
    }

    /**
     * @param \DateTime $from
     * @return AmazonDataIterator
     */
    public function getInitialOrders(\DateTime $from)
    {
        $now    = $this->getNowDate();
        $from   = $this->validateFrom($from, $now);
        $filter = $this
            ->filtersFactory
            ->createCreateTimeRangeFilter($from, $now);

        return $this->getOrders($filter);
    }

    /**
     * @param Response $response
     * @return bool
     */
    protected function getStatusFromResponse(Response $response)
    {
        if (null === $this->namespace) {
            throw new \LogicException('Namespace must be initialized!');
        }
        $xml  = $response->xml()->children($this->namespace);
        $root = RestClient::GET_SERVICE_STATUS . 'Result';

        return (string)$xml->{$root}->Status === RestClient::STATUS_GREEN;
    }

    /**
     * @param Filter $filter
     * @return AmazonDataIterator
     */
    protected function getOrders(Filter $filter)
    {
        $loader = new OrderLoader($this->amazonClient, $filter, $this->namespace);

        return new AmazonDataIterator($loader);
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

    /**
     * @param \DateTime $from
     * @param \DateTime $now
     * @return \DateTime
     *
     * Check that from time not > now after now time was subbed.
     */
    protected function validateFrom(\DateTime $from, \DateTime $now)
    {
        if($from >= $now) {
            $from = clone $now;
            $from->sub(new \DateInterval('PT3M'));
        }

        return $from;
    }
}
