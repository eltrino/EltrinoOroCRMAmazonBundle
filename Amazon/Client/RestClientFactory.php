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
namespace Eltrino\OroCrmAmazonBundle\Amazon\Client;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

use Eltrino\OroCrmAmazonBundle\Amazon\DefaultAuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Guzzle\Http\Client;

class RestClientFactory implements RestClientFactoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    
    /**
     * {@inheritdoc}
     */
    public function create($baseUrl, $keyId, $secret, $merchantId, $marketplaceId)
    {
        $client      = new Client($baseUrl);
        $authHandler = new DefaultAuthorizationHandler($keyId, $secret, $merchantId, $marketplaceId);

        $restClient = new RestClient($client, $authHandler);
        if ($restClient instanceof LoggerAwareInterface && $this->logger) {
            $restClient->setLogger($this->logger);
        }
        
        return $restClient;
    }
}
