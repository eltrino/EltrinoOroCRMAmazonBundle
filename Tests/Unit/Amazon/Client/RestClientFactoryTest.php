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
namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\Amazon\Client;

use Eltrino\OroCrmAmazonBundle\Amazon\Client\RestClientFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\DefaultAuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Guzzle\Http\Client;

class RestClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var RestClientFactory */
    protected $object;

    public function setUp()
    {
        $this->object = new RestClientFactory();
    }

    public function testCreate()
    {
        $restClient = new RestClient(
            new Client('url'),
            new DefaultAuthorizationHandler('keiId', 'secret', 'merchantId', 'marketplaceId')
        );
        $this->assertEquals(
            $restClient,
            $this->object->create('url', 'keiId', 'secret', 'merchantId', 'marketplaceId')
        );
    }
}
