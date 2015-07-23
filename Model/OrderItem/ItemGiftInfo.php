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
     * @var float
     */
    protected $giftWrapPriceAmount;

    /**
     * @var string
     */
    protected $giftWrapLevel;

    /**
     * @param string  $giftMessageText
     * @param string  $giftWrapPriceCurrencyId
     * @param float   $giftWrapPriceAmount
     * @param string  $giftWrapLevel
     */
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
     * @return float
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
