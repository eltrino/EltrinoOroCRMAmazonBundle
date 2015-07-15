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
namespace OroCRM\Bundle\AmazonBundle\Amazon\Api;

interface AuthorizationHandler
{
    /**
     * Retrieves key id
     * @return string
     */
    public function getKeyId();

    /**
     * Retrieves secret
     * @return string
     */
    public function getSecret();

    /**
     * Retrieves merchant id
     * @return string
     */
    public function getMerchantId();

    /**
     * Retrieves marketplace id
     * @return string
     */
    public function getMarketplaceId();

    /**
     * Formats date as ISO 8601 timestamp
     * @return string
     */
    public function getFormattedTimestamp();

    /**
     * Retrieves service version
     * @return string
     */
    public function getVersion();

    /**
     * Retrieves signature version
     * @return string
     */
    public function getSignatureVersion();

    /**
     * Retrieves signature method
     * @return string
     */
    public function getSignatureMethod();

    /**
     * Retrieves signature for request
     * @param array() $parameters
     * @param string $endpointUrl
     * @return string
     */
    public function getSignature($parameters, $endpointUrl);
}
