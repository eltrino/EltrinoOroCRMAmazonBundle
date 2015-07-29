<?php

namespace Eltrino\OroCrmAmazonBundle\Provider\Iterator;

interface NextTokenLoaderInterface
{
    /**
     * @return null|string
     */
    public function getNextToken();

    /**
     * @return boolean
     */
    public function isFirstRequestSend();

    /**
     * @param int $batchSize
     * @return array
     */
    public function load($batchSize);
}
