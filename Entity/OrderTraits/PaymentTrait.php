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
}
