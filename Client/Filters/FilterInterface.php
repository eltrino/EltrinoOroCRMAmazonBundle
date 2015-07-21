<?php

namespace OroCRM\Bundle\AmazonBundle\Client\Filters;

interface FilterInterface
{
    /**
     * @param array $parameters
     * @return array
     */
    public function process(array $parameters);
}
