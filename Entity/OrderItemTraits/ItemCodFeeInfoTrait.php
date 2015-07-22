<?php

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

    /**
     * @return string
     */
    public function getCodFeeCurrencyId()
    {
        return $this->codFeeCurrencyId;
    }

    /**
     * @return string
     */
    public function getCodFeeAmount()
    {
        return $this->codFeeAmount;
    }

    /**
     * @return string
     */
    public function getCodFeeDiscountCurrencyId()
    {
        return $this->codFeeDiscountCurrencyId;
    }

    /**
     * @return string
     */
    public function getCodFeeDiscountAmount()
    {
        return $this->codFeeDiscountAmount;
    }
}
