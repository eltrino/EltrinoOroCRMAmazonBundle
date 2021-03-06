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

use Eltrino\OroCrmAmazonBundle\Model\Order\Payment;
use Eltrino\OroCrmAmazonBundle\Model\Order\Shipping;

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
    public function getCurrencyId()
    {
        return $this->currencyId;
    }
    
    /**
     * @param string $currencyId
     * @return $this
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     * @return $this
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPaymentMethodDetail()
    {
        return $this->paymentMethodDetail;
    }
    
    /**
     * @param string $paymentMethodDetail
     * @return $this
     */
    public function setPaymentMethodDetail($paymentMethodDetail)
    {
        $this->paymentMethodDetail = $paymentMethodDetail;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }
    
    /**
     * @param float $totalAmount
     * @return $this
     */
    public function setTotalAmount($totalAmount)
    {
        if (!is_null($totalAmount)) {
            $totalAmount = (float)$totalAmount;
        }
        $this->totalAmount = $totalAmount;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getShipServiceLevel()
    {
        return $this->shipServiceLevel;
    }
    
    /**
     * @param string $shipServiceLevel
     * @return $this
     */
    public function setShipServiceLevel($shipServiceLevel)
    {
        $this->shipServiceLevel = $shipServiceLevel;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getShipmentServiceLevelCategory()
    {
        return $this->shipmentServiceLevelCategory;
    }
    
    /**
     * @param string $shipmentServiceLevelCategory
     * @return $this
     */
    public function setShipmentServiceLevelCategory($shipmentServiceLevelCategory)
    {
        $this->shipmentServiceLevelCategory = $shipmentServiceLevelCategory;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsShipped()
    {
        return $this->numberOfItemsShipped;
    }
    
    /**
     * @param integer $numberOfItemsShipped
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setNumberOfItemsShipped($numberOfItemsShipped)
    {
        if (!is_null($numberOfItemsShipped)) {
            if (!is_int($numberOfItemsShipped) && !ctype_digit($numberOfItemsShipped)) {
                throw new \InvalidArgumentException(sprintf(
                        "Expected integer value for numberOfItemsShipped. Received %s",
                        $numberOfItemsShipped
                    ));
            }
            $numberOfItemsShipped = (int)$numberOfItemsShipped;
        }
        $this->numberOfItemsShipped = $numberOfItemsShipped;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsUnshipped()
    {
        return $this->numberOfItemsUnshipped;
    }
    
    /**
     * @param integer $numberOfItemsUnshipped
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setNumberOfItemsUnshipped($numberOfItemsUnshipped)
    {
        if (!is_null($numberOfItemsUnshipped)) {
            if (!is_int($numberOfItemsUnshipped) && !ctype_digit($numberOfItemsUnshipped)) {
                throw new \InvalidArgumentException(sprintf(
                        "Expected integer value for numberOfItemsUnshipped. Received %s",
                        $numberOfItemsUnshipped
                    ));
            }
            $numberOfItemsUnshipped = (int)$numberOfItemsUnshipped;
        }
        $this->numberOfItemsUnshipped = $numberOfItemsUnshipped;
        
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
