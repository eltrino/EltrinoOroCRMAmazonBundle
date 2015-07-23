<?php

namespace OroCRM\Bundle\AmazonBundle\Model\Order;

/**
 * Class File, Value Object
 * @package OroCRM\Bundle\AmazonBundle\Model
 */
class Payment
{
    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var string
     */
    protected $currencyId;

    /**
     * @var float
     */
    protected $totalAmount;

    /**
     * @param string $paymentMethod
     * @param string $currencyId
     * @param float  $totalAmount
     */
    public function __construct($paymentMethod, $currencyId, $totalAmount)
    {
        $this->paymentMethod = $paymentMethod;
        $this->currencyId    = $currencyId;
        $this->totalAmount   = $totalAmount;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
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
}
