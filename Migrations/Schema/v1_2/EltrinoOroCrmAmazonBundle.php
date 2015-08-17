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

namespace Eltrino\OroCrmAmazonBundle\Migrations\Schema\v1_2;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Doctrine\DBAL\Types\Type;

class EltrinoOroCrmAmazonBundle implements Migration
{
    const ORDER_TABLE = 'eltrino_amazon_order';
    const ORDER_ITEM_TABLE = 'eltrino_amazon_order_items';

    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        if ($schema->hasTable(self::ORDER_TABLE)) {
            $this->alterEltrinoAmazonOrderTable($schema);
        }

        if ($schema->hasTable(self::ORDER_ITEM_TABLE)) {
            $this->alterEltrinoAmazonOrderItemsTable($schema);
        }
    }

    /**
     * @param Schema $schema
     */
    public function alterEltrinoAmazonOrderTable(Schema $schema)
    {
        $table = $schema->getTable(self::ORDER_TABLE);

        if ($table->hasColumn('total_amount')) {
            $column = $table->getColumn('total_amount');
            $column->setType(Type::getType('money'));
            $column->setOptions(['default' => NULL]);
        }
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function alterEltrinoAmazonOrderItemsTable(Schema $schema)
    {
        $table = $schema->getTable(self::ORDER_ITEM_TABLE);

        $convertToMoneyType = [
            'cod_fee_amount',
            'cod_fee_discount_amount',
            'gift_price_amount',
            'item_price_amount',
            'shipping_price_amount'
        ];

        foreach ($convertToMoneyType as $column) {
            if ($table->hasColumn($column)) {
                $column = $table->getColumn($column);
                $column->setType(Type::getType('money'));
                $column->setOptions(['default' => NULL]);
            }
        }

        if ($table->hasColumn('title')) {
            $column = $table->getColumn('title');
            $column->setLength(2048);
        }
    }
} 
