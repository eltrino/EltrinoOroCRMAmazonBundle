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
namespace OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits;

use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemCodFeeInfo;

trait ItemCodFeeInfoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="cod_fee_currency_id", type="string", length=32, nullable=true)
     */
    protected $codFeeCurrencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="cod_fee_amount", type="float", nullable=true)
     */
    protected $codFeeAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_fee_discount_currency_id", type="string", length=32, nullable=true)
     */
    protected $codFeeDiscountCurrencyId;

    /**
     * @var float
     *
     * @ORM\Column(name="cod_fee_discount_amount", type="float", nullable=true)
     */
    protected $codFeeDiscountAmount;

    /**
     * @return ItemCodFeeInfo
     */
    protected function initItemCodFeeInfo()
    {
        return new ItemCodFeeInfo(
            $this->codFeeCurrencyId,
            $this->codFeeAmount,
            $this->codFeeDiscountCurrencyId,
            $this->codFeeDiscountAmount
        );
    }

    /**
     * @param ItemCodFeeInfo $itemCodFeeInfo
     */
    protected function initFromItemCodFeeInfo(ItemCodFeeInfo $itemCodFeeInfo)
    {
        $this->codFeeCurrencyId         = $itemCodFeeInfo->getCodFeeCurrencyId();
        $this->codFeeAmount             = $itemCodFeeInfo->getCodFeeAmount();
        $this->codFeeDiscountCurrencyId = $itemCodFeeInfo->getCodFeeDiscountCurrencyId();
        $this->codFeeDiscountAmount     = $itemCodFeeInfo->getCodFeeDiscountAmount();
    }
}
