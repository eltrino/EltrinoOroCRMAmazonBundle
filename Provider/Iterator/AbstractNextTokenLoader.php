<?php

namespace Eltrino\OroCrmAmazonBundle\Provider\Iterator;

use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Guzzle\Http\Message\Response;

abstract class AbstractNextTokenLoader implements NextTokenLoaderInterface
{
    /** @var bool */
    protected $firstRequestSend = false;

    /** @var RestClient */
    protected $client;

    /** @var null|string */
    protected $nextToken;

    /**
     * @param RestClient $client
     */
    public function __construct(RestClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getNextToken()
    {
        return $this->nextToken;
    }

    /**
     * {@inheritdoc}
     */
    public function isFirstRequestSend()
    {
        return $this->firstRequestSend;
    }

    /**
     * {@inheritdoc}
     */
    public function load($batchSize)
    {
        $elements = [];
        if (!$this->firstRequestSend) {
            $elements = $this->firstLoad();
        }
        $loaded           = count($elements);
        $nextTokenRequest = $this->getNextTokenRequest();

        while (null !== $nextTokenRequest && ($loaded <= $batchSize)) {
            $response  = $this->client->sendRequest($nextTokenRequest);
            $processed = $this->processResponse($nextTokenRequest->getAction(), $response);
            $loaded += count($processed);
            $elements         = array_merge($elements, $processed);
            $nextTokenRequest = $this->getNextTokenRequest();
        }

        return $elements;
    }

    /**
     * @return array
     */
    protected function firstLoad()
    {
        $request                = $this->getFirstRequest();
        $response               = $this->client->sendRequest($request);
        $elements               = $this->processResponse($request->getAction(), $response);
        $this->firstRequestSend = true;

        return $elements;
    }

    /** @return null|Request */
    protected function getNextTokenRequest()
    {
        return null;
    }

    /**
     * @param string   $action
     * @param Response $response
     * @return array
     */
    abstract protected function processResponse($action, Response $response);

    /** @return Request */
    abstract protected function getFirstRequest();
}
