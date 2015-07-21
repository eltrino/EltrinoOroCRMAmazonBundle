<?php

namespace OroCRM\Bundle\AmazonBundle\Client;

interface AuthHandlerInterface
{
    /**
     * @return string
     */
    public function getKeyId();

    /**
     * @return string
     */
    public function getSecret();

    /**
     * @return string
     */
    public function getMerchantId();

    /**
     * @return string
     */
    public function getMarketplaceId();

    /**
     * Formats date as ISO 8601 timestamp
     * @return string
     */
    public function getFormattedTimestamp();

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @return string
     */
    public function getSignatureVersion();

    /**
     * @return string
     */
    public function getSignatureMethod();

    /**
     * @param array  $parameters
     * @param string $baseUrl
     *
     * @return string
     */
    public function getSignature(array $parameters, $baseUrl);
}
