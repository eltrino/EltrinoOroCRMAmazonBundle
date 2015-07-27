<?php

namespace OroCRMPackages\src\OroCRM\Bundle\AmazonBundle\Tests\Unit\Client;

use OroCRM\Bundle\AmazonBundle\Client\AuthHandler;

class AuthHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AuthHandler */
    protected $object;

    public function setUp()
    {
        $this->object = new AuthHandler('testKeyId', 'testSecret', 'testMerchantId', 'testMarketplaceId');
    }

    /**
     * @dataProvider testGettersDataProvider
     * @param        $getter
     * @param        $expected
     * @param string $assertType
     */
    public function testGetters($getter, $expected, $assertType = 'equals')
    {
        switch ($assertType) {
            case 'equals':
                $this->assertEquals($expected, $this->object->{$getter}());
                break;
            case 'regexp':
                $this->assertRegExp($expected, $this->object->{$getter}());
                break;
            default:
                break;
        }
    }

    public function testGetSignature()
    {
        $baseUrl = 'https://mws.amazonservices.com/Orders';
        $merchantId = $this->object->getMerchantId();
        $marketplaceId = $this->object->getMarketplaceId();
        $keyId = $this->object->getKeyId();
        $formattedTimestamp = $this->object->getFormattedTimestamp();
        $version = $this->object->getVersion();
        $signatureVersion = $this->object->getSignatureVersion();
        $signatureMethod = $this->object->getSignatureMethod();
        $parameters = $dataParameters = [
            'Action'             => 'TestAction',
            'SellerId'           => $merchantId,
            'MarketplaceId.Id.1' => $marketplaceId,
            'AWSAccessKeyId'     => $keyId,
            'Timestamp'          => $formattedTimestamp,
            'Version'            => $version,
            'SignatureVersion'   => $signatureVersion,
            'SignatureMethod'    => $signatureMethod,
        ];
        uksort($dataParameters, 'strcmp');

        $data = vsprintf(
            "POST\nmws.amazonservices.com\n/Orders\nAWSAccessKeyId=%s"
            . "&Action=%s&MarketplaceId.Id.1=%s&SellerId=%s&SignatureMethod=%s"
            . "&SignatureVersion=%s&Timestamp=%s&Version=%s",
            array_map(
                function ($value) {
                    return str_replace('%7E', '~', rawurlencode($value));
                },
                $dataParameters
            )
        );

        $this->assertEquals(
            base64_encode(hash_hmac('sha256', $data, $this->object->getSecret(), true)),
            $this->object->getSignature($parameters, $baseUrl)
        );
    }

    /**
     * @return array
     */
    public function testGettersDataProvider()
    {
        $gmdate = str_replace('[_]', '[\d]', gmdate("(Y-m-d\TH:i:)([_]{2})(\.\\0\\0\\0\\Z)", time()));

        return [
            ['getKeyId', 'testKeyId'],
            ['getMarketplaceId', 'testMarketplaceId'],
            ['getSecret', 'testSecret'],
            ['getMerchantId', 'testMerchantId'],
            ['getFormattedTimestamp', sprintf("/%s/", $gmdate), 'regexp'],
            ['getSignatureMethod', AuthHandler::SIGNATURE_METHOD],
            ['getSignatureVersion', AuthHandler::SIGNATURE_VERSION],
            ['getVersion', AuthHandler::SERVICE_VERSION]
        ];
    }
}
