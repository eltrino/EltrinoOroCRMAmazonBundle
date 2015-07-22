<?php

namespace OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits;

use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemGiftInfo;

trait ItemGiftInfoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="gift_message_text", type="string", length=2048, nullable=true)
     */
    protected $giftMessageText;

    /**
     * @var string
     *
     * @ORM\Column(name="gift_price_currency_id", type="string", length=32, nullable=true)
     */
    protected $giftWrapPriceCurrencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="gift_price_amount", type="float", nullable=true)
     */
    protected $giftWrapPriceAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="gift_level", type="string", length=256, nullable=true)
     */
    protected $giftWrapLevel;

    /**
     * @return ItemGiftInfo
     */
    protected function initItemGiftInfo()
    {
        return new ItemGiftInfo(
            $this->giftMessageText,
            $this->giftWrapPriceCurrencyId,
            $this->giftWrapPriceAmount,
            $this->giftWrapLevel
        );
    }

    /**
     * @param ItemGiftInfo $itemGiftInfo
     */
    protected function initFromItemGiftInfo(ItemGiftInfo $itemGiftInfo)
    {
        $this->giftMessageText         = $itemGiftInfo->getGiftMessageText();
        $this->giftWrapPriceCurrencyId = $itemGiftInfo->getGiftWrapPriceCurrencyId();
        $this->giftWrapPriceAmount     = $itemGiftInfo->getGiftWrapPriceAmount();
        $this->giftWrapLevel           = $itemGiftInfo->getGiftWrapLevel();
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
