<?php

namespace OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits;

use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemShippingInfo;

trait ItemShippingInfoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="ship_price_currency", type="string", length=32, nullable=true)
     */
    protected $shippingPriceCurrencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="ship_price_amount", type="float", nullable=true)
     */
    protected $shippingPriceAmount;

    /**
     * @return ItemShippingInfo
     */
    protected function initItemShippingInfo()
    {
        return new itemShippingInfo($this->shippingPriceCurrencyId, $this->shippingPriceAmount);
    }

    /**
     * @param ItemShippingInfo $itemShippingInfo
     */
    protected function initFromItemShippingInfo(ItemShippingInfo $itemShippingInfo)
    {
        $this->shippingPriceCurrencyId = $itemShippingInfo->getShippingPriceCurrencyId();
        $this->shippingPriceAmount     = $itemShippingInfo->getShippingPriceAmount();
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
