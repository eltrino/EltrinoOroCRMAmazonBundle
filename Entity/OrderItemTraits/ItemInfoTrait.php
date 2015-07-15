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

use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemInfo;

trait ItemInfoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="order_item_id", type="string", length=60, nullable=true)
     */
    protected $orderItemId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=80, nullable=true)
     */
    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity_ordered", type="integer", nullable=true)
     */
    protected $quantityOrdered;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity_shipped", type="integer", nullable=true)
     */
    protected $quantityShipped;

    /**
     * @var string
     *
     * @ORM\Column(name="item_price_currency_id", type="string", length=32, nullable=true)
     */
    protected $itemPriceCurrencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="item_price_amount", type="float", nullable=true)
     */
    protected $itemPriceAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="item_condition", type="string", length=32, nullable=true)
     */
    protected $condition;

    /**
     * @return ItemInfo
     */
    protected function initItemInfo()
    {
        return new ItemInfo(
            $this->orderItemId,
            $this->title,
            $this->quantityOrdered,
            $this->quantityShipped,
            $this->itemPriceCurrencyId,
            $this->itemPriceAmount,
            $this->condition
        );
    }

    /**
     * @param ItemInfo $itemInfo
     */
    protected function initFromItemInfo(ItemInfo $itemInfo)
    {
        $this->orderItemId         = $itemInfo->getOrderItemId();
        $this->title               = $itemInfo->getTitle();
        $this->quantityOrdered     = $itemInfo->getQuantityOrdered();
        $this->quantityShipped     = $itemInfo->getQuantityShipped();
        $this->itemPriceCurrencyId = $itemInfo->getItemPriceCurrencyId();
        $this->itemPriceAmount     = $itemInfo->getItemPriceAmount();
        $this->condition           = $itemInfo->getCondition();
    }
}
