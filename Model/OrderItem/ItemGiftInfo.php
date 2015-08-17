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
namespace Eltrino\OroCrmAmazonBundle\Model\OrderItem;

class ItemGiftInfo
{
    /**
     * @var string
     */
    private $giftMessageText;

    /**
     * @var string
     */
    private $giftWrapPriceCurrencyId;

    /**
     * @var string
     */
    private $giftWrapPriceAmount;

    /**
     * @var string
     */
    private $giftWrapLevel;

    public function __construct($giftMessageText, $giftWrapPriceCurrencyId, $giftWrapPriceAmount,
                                $giftWrapLevel)
    {
        $this->giftMessageText           = $giftMessageText;
        $this->giftWrapPriceCurrencyId   = $giftWrapPriceCurrencyId;
        $this->giftWrapPriceAmount       = empty($giftWrapPriceAmount) ? null : $giftWrapPriceAmount;
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