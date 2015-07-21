<?php

namespace OroCRM\Bundle\AmazonBundle\Model\OrderItem;

class ItemShippingInfo
{
    /**
     * @var string
     */
    protected $shippingPriceCurrencyId;

    /**
     * @var string
     */
    protected $shippingPriceAmount;

    public function __construct($shippingPriceCurrencyId, $shippingPriceAmount)
    {
        $this->shippingPriceCurrencyId = $shippingPriceCurrencyId;
        $this->shippingPriceAmount     = $shippingPriceAmount;
    }

    /**
     * @return string
     */
    public function getShippingPriceCurrencyId()
    {
        return $this->shippingPriceCurrencyId;
    }

    /**
     * @return string
     */
    public function getShippingPriceAmount()
    {
        return $this->shippingPriceAmount;
    }
}
