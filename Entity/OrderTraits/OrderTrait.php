<?php

namespace OroCRM\Bundle\AmazonBundle\Entity\OrderTraits;

trait OrderTrait
{
    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return Shipping
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @return string
     */
    public function getSalesChannel()
    {
        return $this->salesChannel;
    }

    /**
     * @return string
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @return string
     */
    public function getFulfillmentChannel()
    {
        return $this->fulfillmentChannel;
    }

    /**
     * @return string
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return string
     */
    public function getShipServiceLevel()
    {
        return $this->shipServiceLevel;
    }

    /**
     * @return string
     */
    public function getShipmentServiceLevelCategory()
    {
        return $this->shipmentServiceLevelCategory;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsShipped()
    {
        return $this->numberOfItemsShipped;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsUnshipped()
    {
        return $this->numberOfItemsUnshipped;
    }
}
