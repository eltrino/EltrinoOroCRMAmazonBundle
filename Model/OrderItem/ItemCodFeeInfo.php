<?php

namespace OroCRM\Bundle\AmazonBundle\Model\OrderItem;

class ItemCodFeeInfo
{
    /**
     * @var string
     */
    protected $codFeeCurrencyId;

    /**
     * @var string
     */
    protected $codFeeAmount;

    /**
     * @var string
     */
    protected $codFeeDiscountCurrencyId;

    /**
     * @var string
     */
    protected $codFeeDiscountAmount;

    public function __construct(
        $codFeeCurrencyId,
        $codFeeAmount,
        $codFeeDiscountCurrencyId,
        $codFeeDiscountAmount
    ) {
        $this->codFeeCurrencyId         = $codFeeCurrencyId;
        $this->codFeeAmount             = $codFeeAmount;
        $this->codFeeDiscountCurrencyId = $codFeeDiscountCurrencyId;
        $this->codFeeDiscountAmount     = $codFeeDiscountAmount;
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
