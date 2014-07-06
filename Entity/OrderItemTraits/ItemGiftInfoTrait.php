<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
namespace Eltrino\OroCrmAmazonBundle\Entity\OrderItemTraits;

use Eltrino\OroCrmAmazonBundle\Model\OrderItem\ItemGiftInfo;

trait ItemGiftInfoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="gift_message_text", type="string", length=2048, nullable=true)
     */
    private $giftMessageText;

    /**
     * @var string
     *
     * @ORM\Column(name="gift_price_currency_id", type="string", length=32, nullable=true)
     */
    private $giftWrapPriceCurrencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="gift_price_amount", type="float", nullable=true)
     */
    private $giftWrapPriceAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="gift_level", type="string", length=256, nullable=true)
     */
    private $giftWrapLevel;

    /**
     * @return ItemGiftInfo
     */
    protected function initItemGiftInfo()
    {
        return new ItemGiftInfo($this->giftMessageText, $this->giftWrapPriceCurrencyId,
            $this->giftWrapPriceAmount, $this->giftWrapLevel);
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
} 