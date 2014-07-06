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
     * @param Transport $transportEntity
     */
    public function init(Transport $transportEntity)
    {

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
} 