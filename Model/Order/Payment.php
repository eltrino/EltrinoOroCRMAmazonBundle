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

/**
 * Class File, Value Object
 * @package Eltrino\OroCrmAmazonBundle\Model
 */
class Payment
{
    /**
     * @var string
     */
    private $paymentMethod;

    /**
     * @var string
     */
    private $currencyId;

    /**
     * @var string
     */
    private $totalAmount;

    public function __construct($paymentMethod, $currencyId, $totalAmount)
    {
        $this->setPaymentMethod($paymentMethod);
        $this->setCurrencyId($currencyId);
        $this->setTotalAmount($totalAmount);
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

}
