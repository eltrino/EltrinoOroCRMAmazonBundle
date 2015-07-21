<?php

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
        $this->orderItemId         = $orderItemId;
        $this->title               = $title;
        $this->quantityOrdered     = $quantityOrdered;
        $this->quantityShipped     = $quantityShipped;
        $this->itemPriceCurrencyId = $itemPriceCurrencyId;
        $this->itemPriceAmount     = $itemPriceAmount;
        $this->condition           = $condition;
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
