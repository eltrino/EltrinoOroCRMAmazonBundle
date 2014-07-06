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
namespace Eltrino\OroCrmAmazonBundle\Grid;

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
