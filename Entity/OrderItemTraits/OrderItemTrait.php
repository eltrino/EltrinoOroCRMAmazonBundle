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
namespace OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits;

trait OrderItemTrait
{
    /**
     * @return string
     */
    public function getOrderItemId()
    {
        return $this->orderItemId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getQuantityOrdered()
    {
        return $this->quantityOrdered;
    }

    /**
     * @return string
     */
    public function getQuantityShipped()
    {
        return $this->quantityShipped;
    }

    /**
     * @return string
     */
    public function getItemPriceCurrencyId()
    {
        return $this->itemPriceCurrencyId;
    }

    /**
     * @return string
     */
    public function getItemPriceAmount()
    {
        return $this->itemPriceAmount;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }
    /**
     * @return string
     */
    public function getCodFeeCurrencyId()
    {
        return $this->codFeeCurrencyId;
    }

    /**
     * @return string
     */
    public function getCodFeeAmount()
    {
        return $this->codFeeAmount;
    }

    /**
     * @return string
     */
    public function getCodFeeDiscountCurrencyId()
    {
        return $this->codFeeDiscountCurrencyId;
    }

    /**
     * @return string
     */
    public function getCodFeeDiscountAmount()
    {
        return $this->codFeeDiscountAmount;
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
