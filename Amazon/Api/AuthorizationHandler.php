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
namespace Eltrino\OroCrmAmazonBundle\Amazon\Api;

interface AuthorizationHandler
{
    /**
     * Retrieves key id
     * @return string
     */
    function getKeyId();

    /**
     * Retrieves secret
     * @return string
     */
    function getSecret();

    /**
     * Retrieves merchant id
     * @return string
     */
    function getMerchantId();

    /**
     * Retrieves marketplace id
     * @return string
     */
    function getMarketplaceId();

    /**
     * Retrieves signature version
     * @return string
     */
    function getSignatureVersion();

    /**
     * Retrieves signature method
     * @return string
     */
    function getSignatureMethod();

    /**
     * Retrieves signature for request
     * @param array() $parameters
     * @param string $endpointUrl
     * @return string
     */
    function getSignature($parameters, $endpointUrl);
} 
