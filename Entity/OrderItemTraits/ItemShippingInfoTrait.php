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

use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemShippingInfo;

trait ItemShippingInfoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="shipping_price_currency_id", type="string", length=32, nullable=true)
     */
    protected $shippingPriceCurrencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="shipping_price_amount", type="float", nullable=true)
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
        $this->shippingPriceCurrencyId    = $itemShippingInfo->getShippingPriceCurrencyId();
        $this->shippingPriceAmount        = $itemShippingInfo->getShippingPriceAmount();
    }
}
