<?php

namespace Eltrino\OroCrmAmazonBundle\Amazon\Client;

use Eltrino\OroCrmAmazonBundle\Amazon\DefaultAuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Guzzle\Http\Client;

class RestClientFactory
{
    /**
     * @param $baseUrl
     * @param $keyId
     * @param $secret
     * @param $merchantId
     * @param $marketplaceId
     *
     * @return RestClient
     */
    public function create($baseUrl, $keyId, $secret, $merchantId, $marketplaceId)
    {
        $client      = new Client($baseUrl);
        $authHandler = new DefaultAuthorizationHandler($keyId, $secret, $merchantId, $marketplaceId);

        return new RestClient($client, $authHandler);
    }
}
