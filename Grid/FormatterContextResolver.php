<?php

namespace OroCRM\Bundle\AmazonBundle\Grid;

use Oro\Bundle\LocaleBundle\Formatter\NumberFormatter;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;

class FormatterContextResolver
{
    /**
     * Return currency from given row
     *
     * @return callable
     */
    public static function getResolverShippingCurrencyClosure()
    {
        return function (ResultRecordInterface $record, $value, NumberFormatter $formatter) {
            return [$record->getValue('shipping_price_currency_id')];
        };
    }

    /**
     * Return currency from given row
     *
     * @return callable
     */
    public static function getResolverGiftCurrencyClosure()
    {
        return function (ResultRecordInterface $record, $value, NumberFormatter $formatter) {
            return [$record->getValue('gift_wrap_price_currency_id')];
        };
    }

    /**
     * Return currency from given row
     *
     * @return callable
     */
    public static function getResolverItemCurrencyClosure()
    {
        return function (ResultRecordInterface $record, $value, NumberFormatter $formatter) {
            return [$record->getValue('item_price_currency_id')];
        };
    }
}
