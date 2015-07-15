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

class ItemInfo
{
    /**
     * @var string
     */
    protected $orderItemId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $quantityOrdered;

    /**
     * @var string
     */
    protected $quantityShipped;

    /**
     * @var string
     */
    protected $itemPriceCurrencyId;

    /**
     * @var string
     */
    protected $itemPriceAmount;

    /**
     * @var string
     */
    protected $condition;

    public function __construct(
        $orderItemId,
        $title,
        $quantityOrdered,
        $quantityShipped,
        $itemPriceCurrencyId,
        $itemPriceAmount,
        $condition
    ) {
        $this->orderItemId           = $orderItemId;
        $this->title                 = $title;
        $this->quantityOrdered       = $quantityOrdered;
        $this->quantityShipped       = $quantityShipped;
        $this->itemPriceCurrencyId   = $itemPriceCurrencyId;
        $this->itemPriceAmount       = $itemPriceAmount;
        $this->condition             = $condition;
    }

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
}
