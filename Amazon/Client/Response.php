<?php

namespace Eltrino\OroCrmAmazonBundle\Amazon\Client;

class Response
{
    /**
     * @var \SimpleXmlElement
     */
    protected $result;

    /**
     * @var string|null
     */
    protected $nextToken;

    /**
     * @var string
     */
    protected $resultRoot;

    /**
     * @param \SimpleXmlElement $result
     * @param string            $resultRoot
     */
    public function __construct(\SimpleXmlElement $result, $resultRoot)
    {
        $this->result = $result;
        $this->resultRoot = $resultRoot;
    }

    /**
     * @return \SimpleXmlElement
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string|null
     */
    public function getNextToken()
    {
        return $this->nextToken;
    }

    /**
     * @return string
     */
    public function getResultRoot()
    {
        return $this->resultRoot;
    }

    /**
     * @param string $nextToken
     */
    public function setNextToken($nextToken)
    {
        $this->nextToken = $nextToken;
    }
}
