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
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Guzzle\Http\ClientInterface;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

abstract class AbstractRestClientImpl
{
    /**
     * @var \Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * @var Api\AuthorizationHandler
     */
    private $authHandler;

    /**
     * @var int
     */
    protected $restoreRate = 0;

    /**
     * @var int
     */
    protected $maxRequestQuote = 0;

    /**
     * @var int
     */
    private $callQty;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string
     */
    protected $action;

    /**
     * @param ClientInterface $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->client = $client;
        $this->authHandler = $authHandler;

        $this->callQty = 0;
    }

    /**
     * @param Filter $filter
     * @return mixed|\SimpleXMLElement[]
     */
    protected function makeRequest(Filter $filter = null)
    {
        if ($filter) {
            $this->parameters = $filter->process($this->getParameters());
        }
        $this->signParameters();

        while (true) {
            try {
                $this->processRestoreSecs();
                $response = $this->client->post(null, array(), $this->parameters)
                    ->send();
                break;
            } catch(\Guzzle\Http\Exception\ServerErrorResponseException $e) {
                sleep($this->restoreRate * $this->maxRequestQuote);
                $this->callQty = 0;
            }
        }

        return $this->formatResponse($response);
    }

    /**
     * @param $response
     * @return mixed
     */
    private function formatResponse($response)
    {
        $response = $response->xml();
        $response->registerXPathNamespace('c', 'https://mws.amazonservices.com/Orders/2013-09-01');

        return $response;
    }

    public function getParameters()
    {
        return array
        (
            'SellerId'           => $this->authHandler->getMerchantId(),
            'MarketplaceId.Id.1' => $this->authHandler->getMarketplaceId(),
            'Action'             => $this->getAction(),
            'AWSAccessKeyId'     => $this->authHandler->getKeyId(),
            'Timestamp'          => $this->authHandler->getFormattedTimestamp(),
            'Version'            => $this->authHandler->getVersion(),
            'SignatureVersion'   => $this->authHandler->getSignatureVersion(),
            'SignatureMethod'    => $this->authHandler->getSignatureMethod(),
        );
    }

    /**
     * @param $parameters
     * @return mixed
     */
    protected function signParameters()
    {
        $signature = $this->authHandler->getSignature($this->parameters, $this->client->getBaseUrl());
        $this->parameters['Signature'] = $signature;
    }

    /**
     * @return mixed
     */
    protected function getAction()
    {
        return $this->action;
    }

    private function processRestoreSecs()
    {
        $this->callQty ++;
        if ($this->callQty == $this->maxRequestQuote) {
            sleep($this->restoreRate * $this->maxRequestQuote);
            $this->callQty = 0;
        }
    }
} 