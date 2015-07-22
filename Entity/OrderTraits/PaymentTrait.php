<?php

namespace OroCRM\Bundle\AmazonBundle\Entity\OrderTraits;

use OroCRM\Bundle\AmazonBundle\Model\Order\Payment;

trait PaymentTrait
{
    /**
     * @var float
     *
     * @ORM\Column(name="total_amount", type="float", nullable=true)
     */
    protected $totalAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_id", type="string", length=32, nullable=true)
     */
    protected $currencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_method", type="string", length=60, nullable=true)
     */
    protected $paymentMethod;

    /**
     * @return Payment
     */
    protected function initPayment()
    {
        return new Payment($this->paymentMethod, $this->currencyId, $this->totalAmount);
    }

    /**
     * @param Payment $payment
     */
    protected function initFromPayment(Payment $payment)
    {
        $this->totalAmount   = $payment->getTotalAmount();
        $this->currencyId    = $payment->getCurrencyId();
        $this->paymentMethod = $payment->getPaymentMethod();
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
}
