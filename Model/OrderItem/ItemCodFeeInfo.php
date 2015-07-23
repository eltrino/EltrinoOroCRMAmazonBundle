<?php

namespace OroCRM\Bundle\AmazonBundle\Model\OrderItem;

class ItemCodFeeInfo
{
    /**
     * @var string
     */
    protected $codFeeCurrencyId;

    /**
     * @var float
     */
    protected $codFeeAmount;

    /**
     * @var string
     */
    protected $codFeeDiscountCurrencyId;

    /**
     * @var float
     */
    protected $codFeeDiscountAmount;

    /**
     * @param string $codFeeCurrencyId
     * @param float  $codFeeAmount
     * @param string $codFeeDiscountCurrencyId
     * @param float  $codFeeDiscountAmount
     */
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
     * @return float
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
     * @return float
     */
    public function getCodFeeDiscountAmount()
    {
        return $this->codFeeDiscountAmount;
    }
}
