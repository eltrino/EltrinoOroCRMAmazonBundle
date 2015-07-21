<?php

namespace OroCRM\Bundle\AmazonBundle\Model\OrderItem;

class ItemGiftInfo
{
    /**
     * @var string
     */
    protected $giftMessageText;

    /**
     * @var string
     */
    protected $giftWrapPriceCurrencyId;

    /**
     * @var string
     */
    protected $giftWrapPriceAmount;

    /**
     * @var string
     */
    protected $giftWrapLevel;

    public function __construct(
        $giftMessageText,
        $giftWrapPriceCurrencyId,
        $giftWrapPriceAmount,
        $giftWrapLevel
    ) {
        $this->giftMessageText         = $giftMessageText;
        $this->giftWrapPriceCurrencyId = $giftWrapPriceCurrencyId;
        $this->giftWrapPriceAmount     = $giftWrapPriceAmount;
        $this->giftWrapLevel           = $giftWrapLevel;
    }

    /**
     * @return string
     */
    public function getGiftMessageText()
    {
        return $this->giftMessageText;
    }

    /**
     * @return string
     */
    public function getGiftWrapPriceCurrencyId()
    {
        return $this->giftWrapPriceCurrencyId;
    }

    /**
     * @return string
     */
    public function getGiftWrapPriceAmount()
    {
        return $this->giftWrapPriceAmount;
    }

    /**
     * @return string
     */
    public function getGiftWrapLevel()
    {
        return $this->giftWrapLevel;
    }
}
