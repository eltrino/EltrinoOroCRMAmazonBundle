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
namespace Eltrino\OroCrmAmazonBundle\Amazon;

use Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler;

class DefaultAuthorizationHandler implements AuthorizationHandler
{
    const SERVICE_VERSION   = '2013-09-01';

    const SIGNATURE_VERSION = '2';

    const SIGNATURE_METHOD  = 'HmacSHA256';

    /**
     * @var string
     */
    private $keyId;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $marketplaceId;

    function __construct($keyId, $secret, $merchantId, $marketplaceId)
    {
        $this->keyId         = $keyId;
        $this->secret        = $secret;
        $this->merchantId    = $merchantId;
        $this->marketplaceId = $marketplaceId;
    }

    /**
     * Retrieves key id
     * @return string
     */
    public function getKeyId()
    {
        return $this->keyId;
    }

    /**
     * Retrieves marketplace id
     * @return string
     */
    public function getMarketplaceId()
    {
        return $this->marketplaceId;
    }

    /**
     * Retrieves merchant id
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Retrieves secret
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Formats date as ISO 8601 timestamp
     * @return string
     */
    public function getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::SERVICE_VERSION;
    }

    /**
     * @return string
     */
    public function getSignatureVersion()
    {
        return self::SIGNATURE_VERSION;
    }

    /**
     * @return string
     */
    public function getSignatureMethod()
    {
        return self::SIGNATURE_METHOD;
    }

    /**
     * @param $parameters
     * @param $endpointUrl
     * @return string
     */
    public function getSignature($parameters, $endpointUrl)
    {
        $data = 'POST';
        $data .= "\n";

        $endpoint = parse_url($endpointUrl);

        $data .= $endpoint['host'];
        $data .= "\n";

        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }

        $uriEncoded = implode("/", array_map(array($this, "urlEncode"), explode("/", $uri)));
        $data .= $uriEncoded;

        $data .= "\n";
        uksort($parameters, 'strcmp');

        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->urlEncode($value);
        }

        $string = implode('&', $queryParameters);

        $data .= $string;
        $string = $this->sign($data, $this->getSecret());

        return $string;
    }

    /**
     * @param $value
     * @return string
     */
    private function urlEncode($value) {
        return str_replace('%7E', '~', rawurlencode($value));
    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
    private function sign($data, $key)
    {
        return base64_encode(
            hash_hmac('sha256', $data, $key, true)
        );
    }
} 