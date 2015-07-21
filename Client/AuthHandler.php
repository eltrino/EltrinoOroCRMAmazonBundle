<?php

namespace OroCRM\Bundle\AmazonBundle\Client;

class AuthHandler implements AuthHandlerInterface
{
    const SERVICE_VERSION = '2013-09-01';

    const SIGNATURE_VERSION = '2';

    const SIGNATURE_METHOD = 'HmacSHA256';

    /**
     * @var string
     */
    protected $keyId;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $marketplaceId;

    /**
     * @param string $keyId
     * @param string $secret
     * @param string $merchantId
     * @param string $marketplaceId
     */
    public function __construct($keyId, $secret, $merchantId, $marketplaceId)
    {
        $this->keyId         = $keyId;
        $this->secret        = $secret;
        $this->merchantId    = $merchantId;
        $this->marketplaceId = $marketplaceId;
    }

    /**
     * @return string
     */
    public function getKeyId()
    {
        return $this->keyId;
    }

    /**
     * @return string
     */
    public function getMarketplaceId()
    {
        return $this->marketplaceId;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
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
     * @param array  $parameters
     * @param string $baseUrl
     *
     * @return string
     */
    public function getSignature(array $parameters, $baseUrl)
    {
        $data = 'POST';
        $data .= "\n";

        $endpoint = parse_url($baseUrl);

        $data .= $endpoint['host'];
        $data .= "\n";

        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }

        $uriEncoded = implode("/", array_map([$this, "urlEncode"], explode("/", $uri)));
        $data .= $uriEncoded;

        $data .= "\n";
        uksort($parameters, 'strcmp');
        $queryParameters = [];
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
    protected function urlEncode($value)
    {
        return str_replace('%7E', '~', rawurlencode($value));
    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
    protected function sign($data, $key)
    {
        return base64_encode(
            hash_hmac('sha256', $data, $key, true)
        );
    }
}
