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
namespace Eltrino\OroCrmAmazonBundle\Tests\Amazon;

use Eltrino\OroCrmAmazonBundle\Amazon\CheckRestClientImpl;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class CheckRestClientImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Guzzle\Http\ClientInterface
     * @Mock Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler
     */
    private $authHandler;

    /**
     * @var \Guzzle\Http\Message\RequestInterface
     * @Mock Guzzle\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @var \Guzzle\Http\Message\Response
     * @Mock Guzzle\Http\Message\Response
     */
    private $response;

    /**
     * @var \SimpleXmlElement
     */
    private $responseXml;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter
     * @Mock Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter
     */
    private $filter;

    /**
     * @var CheckRestClientImpl
     */
    private $checkRestClient;

    public function setUp()
    {
        MockAnnotations::init($this);

        $this->responseXml = new \SimpleXMLElement('<Response><GetServiceStatusResult><Status>GREEN</Status></GetServiceStatusResult></Response>');
        $this->checkRestClient = new CheckRestClientImpl($this->client, $this->authHandler);
    }

    public function testGetStatus()
    {
        $this->authHandler->expects($this->once())
            ->method('getSignature');

        $this->client->expects($this->once())
            ->method('post')
            ->will($this->returnValue($this->request));

        $this->request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('xml')
            ->will($this->returnValue($this->responseXml));

        $response = $this->checkRestClient->getStatus($this->filter);

        $this->assertEquals(true, $response);
    }
}
