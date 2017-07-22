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
namespace Eltrino\OroCrmAmazonBundle\Entity\OrderTraits;

use Eltrino\OroCrmAmazonBundle\Entity\OrderTraits\ShippingTrait;
use Eltrino\OroCrmAmazonBundle\Entity\OrderTraits\PaymentTrait;
use Eltrino\OroCrmAmazonBundle\Model\Order\OrderDetails;

trait OrderDetailsTrait
{
    use PaymentTrait;
    use ShippingTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchase_date", type="datetime")
     */
    private $purchaseDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="customer_name", type="string", length=255)
     */
    private $customerName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sales_channel", type="string", length=60, nullable=true)
     */
    private $salesChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="order_type", type="string", length=60, nullable=true)
     */
    private $orderType;

    /**
     * @var string
     *
     * @ORM\Column(name="fulfillment_channel", type="string", length=60, nullable=true)
     */
    private $fulfillmentChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", length=60, nullable=true)
     */
    private $orderStatus;
    
    /**
     * @var string
     *
     * @ORM\Column(name="seller_order_id", type="string", length=60)
     */
    private $sellerOrderId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="earliest_ship_date", type="datetime")
     */
    private $earliestShipDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="latest_ship_date", type="datetime")
     */
    private $latestShipDate;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_premium_order", type="boolean")
     */
    private $isPremiumOrder;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_replacement_order", type="boolean")
     */
    private $isReplacementOrder;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_business_order", type="boolean")
     */
    private $isBusinessOrder;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_prime", type="boolean")
     */
    private $isPrime;
    
    /**
     * @param OrderDetails $orderDetails
     */
    protected function initFromOrderDetails(OrderDetails $orderDetails)
    {
        $this->purchaseDate       = $orderDetails->getPurchaseDate();
        $this->customerName       = $orderDetails->getCustomerName();
        $this->salesChannel       = $orderDetails->getSalesChannel();
        $this->orderType          = $orderDetails->getOrderType();
        $this->fulfillmentChannel = $orderDetails->getFulfillmentChannel();
        $this->orderStatus        = $orderDetails->getOrderStatus();
        $this->sellerOrderId      = $orderDetails->getSellerOrderId();
        $this->earliestShipDate   = $orderDetails->getEarliestShipDate();
        $this->latestShipDate     = $orderDetails->getLatestShipDate();
        $this->isPremiumOrder     = $orderDetails->getIsPremiumOrder();
        $this->isReplacementOrder = $orderDetails->getIsReplacementOrder();
        $this->isBusinessOrder    = $orderDetails->getIsBusinessOrder();
        $this->isPrime            = $orderDetails->getIsPrime();
    }
}
