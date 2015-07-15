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
namespace OroCRM\Bundle\AmazonBundle\Entity\OrderTraits;

use OroCRM\Bundle\AmazonBundle\Entity\OrderTraits\ShippingTrait;
use OroCRM\Bundle\AmazonBundle\Entity\OrderTraits\PaymentTrait;
use OroCRM\Bundle\AmazonBundle\Model\Order\OrderDetails;

trait OrderDetailsTrait
{
    use PaymentTrait;
    use ShippingTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="sales_channel", type="string", length=60, nullable=true)
     */
    protected $salesChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="order_type", type="string", length=60, nullable=true)
     */
    protected $orderType;

    /**
     * @var string
     *
     * @ORM\Column(name="fulfillment_channel", type="string", length=60, nullable=true)
     */
    protected $fulfillmentChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", length=60, nullable=true)
     */
    protected $orderStatus;

    protected function initFromOrderDetails(OrderDetails $orderDetails)
    {
        $this->salesChannel       = $orderDetails->getSalesChannel();
        $this->orderType          = $orderDetails->getOrderType();
        $this->fulfillmentChannel = $orderDetails->getFulfillmentChannel();
        $this->orderStatus        = $orderDetails->getOrderStatus();
    }
}
