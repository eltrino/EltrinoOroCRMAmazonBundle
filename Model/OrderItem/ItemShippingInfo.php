<?php

namespace OroCRM\Bundle\AmazonBundle\Model\OrderItem;

class ItemShippingInfo
{
    /**
     * @var string
     */
    protected $shippingPriceCurrencyId;

    /**
     * @var float
     */
    protected $shippingPriceAmount;

    /**
     * @param string $shippingPriceCurrencyId
     * @param float  $shippingPriceAmount
     */
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
     * @return float
     */
    public function getShippingPriceAmount()
    {
        return $this->shippingPriceAmount;
    }
}
