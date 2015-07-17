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

namespace OroCRM\Bundle\AmazonBundle\Provider\Transport;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use OroCRM\Bundle\AmazonBundle\Amazon\AmazonRestClientFactory;
use OroCRM\Bundle\AmazonBundle\Amazon\AmazonRestClientImpl;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Amazon REST transport
 * used to fetch and pull data to/from Amazon instance
 * with sessionId param using REST requests
 *
 * @package OroCRM\Bundle\AmazonBundle
 */
class AmazonRestTransport implements TransportInterface
{
    /** @var ParameterBag */
    protected $settings;

    /** @var AmazonRestClientImpl */
    protected $amazonClient;

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
        return 'OroCRM\Bundle\AmazonBundle\Entity\AmazonRestTransport';
    }

    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
        $this->settings = $transportEntity->getSettingsBag();
        $amazonRestClientFactory = new AmazonRestClientFactory();
        $this->amazonClient = $amazonRestClientFactory->create(
            $this->settings->get('wsdl_url'),
            $this->settings->get('aws_access_key_id'),
            $this->settings->get('aws_secret_access_key'),
            $this->settings->get('merchant_id'),
            $this->settings->get('marketplace_id')
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
     * @return AmazonRestClientImpl
     */
    public function getAmazonClient()
    {
        return $this->amazonClient;
    }
}
