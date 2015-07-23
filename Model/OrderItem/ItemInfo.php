<?php

namespace OroCRM\Bundle\AmazonBundle\Model\OrderItem;

class ItemInfo
{
    /**
     * @var integer
     */
    protected $orderItemId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var integer
     */
    protected $quantityOrdered;

    /**
     * @var integer
     */
    protected $quantityShipped;

    /**
     * @var string
     */
    protected $itemPriceCurrencyId;

    /**
     * @var float
     */
    protected $itemPriceAmount;

    /**
     * @var string
     */
    protected $condition;

    /**
     * @param integer $orderItemId
     * @param string  $title
     * @param integer $quantityOrdered
     * @param integer $quantityShipped
     * @param string  $itemPriceCurrencyId
     * @param float   $itemPriceAmount
     * @param string  $condition
     */
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
     * @return integer
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
     * @return integer
     */
    public function getQuantityOrdered()
    {
        return $this->quantityOrdered;
    }

    /**
     * @return integer
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
     * @return float
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
