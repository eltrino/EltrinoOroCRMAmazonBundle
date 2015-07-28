<?php

namespace Eltrino\OroCrmAmazonBundle\Provider\Iterator;

use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Guzzle\Http\Message\Response;

abstract class AbstractNextTokenLoader
{
    /** @var bool */
    protected $firstRequestSend = false;

    protected $client;

    protected $nextToken;

    public function __construct(RestClient $client)
    {
        $this->client = $client;
    }

    public function load($batchSize)
    {
        $elements = [];
        if (!$this->firstRequestSend) {
            $elements = $this->firstLoad();
        }
        $loaded = count($elements);
        $nextTokenRequest = $this->getNextTokenRequest();

        while (null !== $nextTokenRequest && ($loaded <= $batchSize)) {
            $response = $this->client->sendRequest($nextTokenRequest);
            $processed = $this->processResponse($nextTokenRequest->getAction(), $response);
            $loaded += count($processed);
            $elements = array_merge($elements, $processed);
        }

        return $elements;
    }

    public function loadNext($batchSize)
    {
        $loaded = 0;
        $nextTokenRequest = $this->getNextTokenRequest();
        $elements = [];
        while (null !== $nextTokenRequest && ($loaded <= $batchSize)) {
            $response = $this->client->sendRequest($nextTokenRequest);
            $processed = $this->processResponse($nextTokenRequest->getAction(), $response);
            $loaded += count($processed);
            $elements = array_merge($elements, $processed);
        }
    }

    protected function firstLoad()
    {
        $request  = $this->getFirstRequest();
        $response = $this->client->sendRequest($request);
        $elements = $this->processResponse($request->getAction(), $response);
        $this->firstRequestSend = true;

        return $elements;
    }

    /**
     * @param string   $action
     * @param Response $response
     * @return array
     */
    abstract protected function processResponse($action, Response $response);

    /** @return Request */
    abstract protected function getFirstRequest();

    /** @return null|Request */
    protected function getNextTokenRequest()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getNextToken()
    {
        return $this->nextToken;
    }

    /**
     * @return boolean
     */
    public function isFirstRequestSend()
    {
        return $this->firstRequestSend;
    }
}
