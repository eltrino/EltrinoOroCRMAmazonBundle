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
namespace Eltrino\OroCrmAmazonBundle\Model\Order;
use Eltrino\OroCrmAmazonBundle\Model\Order\Payment;
use Eltrino\OroCrmAmazonBundle\Model\Order\Shipping;

class OrderDetails
{
    /**
     * @var string
     */
    private $salesChannel;

    /**
     * @var string
     */
    private $orderType;

    /**
     * @var string
     */
    private $fulfillmentChannel;

    /**
     * @var string
     */
    private $orderStatus;

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var Shipping
     */
    private $shipping;

    function __construct($salesChannel, $orderType, $fulfillmentChannel, $orderStatus, Payment $payment, Shipping $shipping)
    {
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
