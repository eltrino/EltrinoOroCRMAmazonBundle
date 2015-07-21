<?php

namespace OroCRM\Bundle\AmazonBundle\Model\Order;

use OroCRM\Bundle\AmazonBundle\Model\Order\Payment;
use OroCRM\Bundle\AmazonBundle\Model\Order\Shipping;

class OrderDetails
{
    /**
     * @var string
     */
    protected $salesChannel;

    /**
     * @var string
     */
    protected $orderType;

    /**
     * @var string
     */
    protected $fulfillmentChannel;

    /**
     * @var string
     */
    protected $orderStatus;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @var Shipping
     */
    protected $shipping;

    public function __construct(
        $salesChannel,
        $orderType,
        $fulfillmentChannel,
        $orderStatus,
        Payment $payment,
        Shipping $shipping
    ) {
        $this->salesChannel       = $salesChannel;
        $this->orderType          = $orderType;
        $this->fulfillmentChannel = $fulfillmentChannel;
        $this->orderStatus        = $orderStatus;
        $this->payment            = $payment;
        $this->shipping           = $shipping;
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
    public function getFulfillmentChannel()
    {
        return $this->fulfillmentChannel;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

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

}
