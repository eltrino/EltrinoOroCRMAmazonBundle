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

    /**
     * @var \DateTime
     */
    private $purchaseDate;
    
    /**
     * @var string
     */
    private $customerName;
    
    /**
     * @var string
     */
    private $sellerOrderId;

    /**
     * @var \DateTime
     */
    private $earliestShipDate;
    
    /**
     * @var \DateTime
     */
    private $latestShipDate;
    
    /**
     * @var boolean
     */
    private $isPremiumOrder;
    
    /**
     * @var boolean
     */
    private $isReplacementOrder;
    
    /**
     * @var boolean
     */
    private $isBusinessOrder;
    
    /**
     * @var boolean
     */
    private $isPrime;

    /**
     * @param string $salesChannel
     * @param string $orderType
     * @param string $fulfillmentChannel
     * @param string $orderStatus
     * @param Payment $payment
     * @param Shipping $shipping
     * @param \DateTime|string $purchaseDate
     * @param string $customerName
     * @param string $sellerOrderId
     * @param \DateTime|string $earliestShipDate
     * @param \DateTime|string $latestShipDate
     * @param boolean $isPremiumOrder
     * @param boolean $isReplacementOrder
     * @param boolean $isBusinessOrder
     * @param boolean $isPrime
     */
    function __construct(
        $salesChannel=null, 
        $orderType=null, 
        $fulfillmentChannel=null, 
        $orderStatus=null, 
        Payment $payment=null, 
        Shipping $shipping=null,
        $purchaseDate=null,
        $customerName=null,
        $sellerOrderId=null,
        $earliestShipDate=null,
        $latestShipDate=null,
        $isPremiumOrder=null,
        $isReplacementOrder=null,
        $isBusinessOrder=null,
        $isPrime=null
    ) {
        $this->setSalesChannel($salesChannel);
        $this->setOrderType($orderType);
        $this->setFulfillmentChannel($fulfillmentChannel);
        $this->setOrderStatus($orderStatus);
        $this->setPayment($payment);
        $this->setShipping($shipping);
        if (is_string($purchaseDate)) {
            $purchaseDate = new \DateTime($purchaseDate);
        }
        $this->setPurchaseDate($purchaseDate);
        $this->setCustomerName($customerName);
        $this->setSellerOrderId($sellerOrderId);
        if (is_string($earliestShipDate)) {
            $earliestShipDate = new \DateTime($earliestShipDate);
        }
        $this->setEarliestShipDate($earliestShipDate);
        if (is_string($latestShipDate)) {
            $latestShipDate = new \DateTime($latestShipDate);
        }
        $this->setLatestShipDate($latestShipDate);
        if (!is_null($isPremiumOrder)) {
            $isPremiumOrder = (bool)$isPremiumOrder;
        }
        $this->setIsPremiumOrder($isPremiumOrder);
        $this->setIsReplacementOrder($isReplacementOrder);
        $this->setIsBusinessOrder($isBusinessOrder);
        $this->setIsPrime($isPrime);
    }

    /**
     * @return string
     */
    public function getSalesChannel()
    {
        return $this->salesChannel;
    }
    
    /**
     * @param string $salesChannel
     * @return $this
     */
    public function setSalesChannel($salesChannel)
    {
        $this->salesChannel = $salesChannel;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderType()
    {
        return $this->orderType;
    }
    
    /**
     * @param string $orderType
     * @return $this
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getFulfillmentChannel()
    {
        return $this->fulfillmentChannel;
    }
    
    /**
     * @param string $fulfillmentChannel
     * @return $this
     */
    public function setFulfillmentChannel($fulfillmentChannel)
    {
        $this->fulfillmentChannel = $fulfillmentChannel;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }
    
    /**
     * @param string $orderStatus
     * @return $this
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
        
        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }
    
    /**
     * @param Payment|null $payment
     * @return $this
     */
    public function setPayment(Payment $payment=null)
    {
        $this->payment = $payment;
        
        return $this;
    }

    /**
     * @return Shipping
     */
    public function getShipping()
    {
        return $this->shipping;
    }
    
    /**
     * @param Shipping|null $shipping
     * @return $this
     */
    public function setShipping(Shipping $shipping = null)
    {
        $this->shipping = $shipping;
        
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    /**
     * @param \DateTime $purchaseDate
     * @return $this
     */
    public function setPurchaseDate(\DateTime $purchaseDate=null)
    {
        $this->purchaseDate = $purchaseDate;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }
    
    /**
     * @param string $customerName
     * @return $this
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSellerOrderId()
    {
        return $this->sellerOrderId;
    }
    
    /**
     * @param string $sellerOrderId
     * @return $this
     */
    public function setSellerOrderId($sellerOrderId)
    {
        $this->sellerOrderId = $sellerOrderId;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getEarliestShipDate()
    {
        return $this->earliestShipDate;
    }
    
    /**
     * @param \DateTime $earliestShipDate
     * @return $this
     */
    public function setEarliestShipDate(\DateTime $earliestShipDate=null)
    {
        $this->earliestShipDate = $earliestShipDate;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getLatestShipDate()
    {
        return $this->latestShipDate;
    }
    
    /**
     * @param \DateTime $latestShipDate
     * @return $this
     */
    public function setLatestShipDate(\DateTime $latestShipDate=null)
    {
        $this->latestShipDate = $latestShipDate;
        
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getIsPremiumOrder()
    {
        return $this->isPremiumOrder;
    }
    
    /**
     * @param boolean $isPremiumOrder
     * @return $this
     */
    public function setIsPremiumOrder($isPremiumOrder)
    {
        if (!is_null($isPremiumOrder)) {
            $isPremiumOrder = (bool)$isPremiumOrder;
        }
        $this->isPremiumOrder = $isPremiumOrder;
        
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getIsReplacementOrder()
    {
        return $this->isReplacementOrder;
    }
    
    /**
     * @param boolean $isReplacementOrder
     * @return $this
     */
    public function setIsReplacementOrder($isReplacementOrder)
    {
        if (!is_null($isReplacementOrder)) {
            $isReplacementOrder = (bool)$isReplacementOrder;
        }
        $this->isReplacementOrder = $isReplacementOrder;
        
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getIsBusinessOrder()
    {
        return $this->isBusinessOrder;
    }
    
    /**
     * @param boolean $isBusinessOrder
     * @return $this
     */
    public function setIsBusinessOrder($isBusinessOrder)
    {
        if (!is_null($isBusinessOrder)) {
            $isBusinessOrder = (bool)$isBusinessOrder;
        }
        $this->isBusinessOrder = $isBusinessOrder;
        
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getIsPrime()
    {
        return $this->isPrime;
    }
    
    /**
     * @param boolean $isPrime
     * @return $this
     */
    public function setIsPrime($isPrime)
    {
        if (!is_null($isPrime)) {
            $isPrime = (bool)$isPrime;
        }
        $this->isPrime = $isPrime;
        
        return $this;
    }
}
