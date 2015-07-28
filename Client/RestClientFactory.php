<?php

namespace OroCRM\Bundle\AmazonBundle\Client;

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
        $authHandler = new AuthHandler($keyId, $secret, $merchantId, $marketplaceId);

        return new RestClient($client, $authHandler);
    }
}
