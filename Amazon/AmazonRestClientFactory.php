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
namespace OroCRM\Bundle\AmazonBundle\Amazon;

use Guzzle\Http\Client;
use Guzzle\Http\ClientInterface;

class AmazonRestClientFactory
{
    /**
     * @param $baseUrl
     * @param $keyId
     * @param $secret
     * @param $merchantId
     * @param $marketplaceId
     * @return AmazonRestClientImpl
     */
    public function create($baseUrl, $keyId, $secret, $merchantId, $marketplaceId)
    {
        $client = new Client($baseUrl);
        $authHandler = new DefaultAuthorizationHandler($keyId, $secret, $merchantId, $marketplaceId);
        return new AmazonRestClientImpl($client, $authHandler);
    }
}
