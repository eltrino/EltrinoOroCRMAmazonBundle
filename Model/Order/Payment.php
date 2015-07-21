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
     * @var string
     */
    protected $totalAmount;

    public function __construct($paymentMethod, $currencyId, $totalAmount)
    {
        $this->paymentMethod = $paymentMethod;
        $this->currencyId    = $currencyId;
        $this->totalAmount   = $totalAmount;
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
