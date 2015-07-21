<?php

namespace OroCRM\Bundle\AmazonBundle\Client\Filters;

class AmazonOrderIdFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $amazonOrderId;

    /**
     * @param string $amazonOrderId
     */
    public function __construct($amazonOrderId)
    {
        $this->amazonOrderId = $amazonOrderId;
    }

    /**
     * @inheritdoc
     */
    public function process(array $parameters)
    {
        $parameters['AmazonOrderId'] = $this->amazonOrderId;

        return $parameters;
    }
}
