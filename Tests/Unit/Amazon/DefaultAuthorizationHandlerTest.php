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
namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\Amazon;

use Eltrino\OroCrmAmazonBundle\Amazon\DefaultAuthorizationHandler;

class DefaultAuthorizationHandlerTest extends \PHPUnit\Framework\TestCase
{
    /** @var DefaultAuthorizationHandler */
    protected $object;

    public function setUp()
    {
        $this->object = new DefaultAuthorizationHandler('testKeyId', 'testSecret', 'testMerchantId', 'testMarketplaceId');
    }

    /**
     * @dataProvider testGettersDataProvider
     * @param        $getter
     * @param        $expected
     */
    public function testGetters($getter, $expected)
    {
        $this->assertEquals($expected, $this->object->{$getter}());
    }

    public function testGetSignature()
    {
        $baseUrl = 'https://mws.amazonservices.com/Orders';
        $merchantId = $this->object->getMerchantId();
        $marketplaceId = $this->object->getMarketplaceId();
        $keyId = $this->object->getKeyId();
        $signatureVersion = $this->object->getSignatureVersion();
        $signatureMethod = $this->object->getSignatureMethod();
        $parameters = $dataParameters = [
            'Action'             => 'TestAction',
            'SellerId'           => $merchantId,
            'MarketplaceId.Id.1' => $marketplaceId,
            'AWSAccessKeyId'     => $keyId,
            'SignatureVersion'   => $signatureVersion,
            'SignatureMethod'    => $signatureMethod,
        ];
        uksort($dataParameters, 'strcmp');

        $data = vsprintf(
            "POST\nmws.amazonservices.com\n/Orders\nAWSAccessKeyId=%s"
            . "&Action=%s&MarketplaceId.Id.1=%s&SellerId=%s&SignatureMethod=%s"
            . "&SignatureVersion=%s",
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
        return [
            ['getKeyId', 'testKeyId'],
            ['getMarketplaceId', 'testMarketplaceId'],
            ['getSecret', 'testSecret'],
            ['getMerchantId', 'testMerchantId'],
            ['getSignatureMethod', DefaultAuthorizationHandler::SIGNATURE_METHOD],
            ['getSignatureVersion', DefaultAuthorizationHandler::SIGNATURE_VERSION],
        ];
    }
}
