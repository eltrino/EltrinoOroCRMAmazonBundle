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
        $this->giftMessageText           = $giftMessageText;
        $this->giftWrapPriceCurrencyId   = $giftWrapPriceCurrencyId;
        $this->giftWrapPriceAmount       = $giftWrapPriceAmount;
        $this->giftWrapLevel             = $giftWrapLevel;
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
