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

namespace Eltrino\OroCrmAmazonBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class EltrinoOroCrmAmazonBundle implements Migration
{
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('aws_access_key_id', 'string', ['notnull' => false, 'length' => 2048]);
        $table->addColumn('aws_secret_access_key', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('merchant_id', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('marketplace_id', 'string', ['notnull' => false, 'length' => 255]);

        /** Generate table eltrino_amazon_order **/
        $table = $schema->createTable('eltrino_amazon_order');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('channel_id', 'integer', ['notnull' => false]);
        $table->addColumn('amazon_order_id', 'string', ['length' => 60]);
        $table->addColumn('marketplace_id', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->addColumn('sales_channel', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('order_type', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('fulfillment_channel', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('order_status', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('total_amount', 'float', ['notnull' => false]);
        $table->addColumn('currency_id', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('payment_method', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('ship_service_level', 'string', ['length' => 300]);
        $table->addColumn('ship_service_level_category', 'string', ['length' => 300]);
        $table->addColumn('number_of_items_shipped', 'integer', ['notnull' => false]);
        $table->addColumn('number_of_items_unshipped', 'integer', ['notnull' => false]);
        $table->addColumn('customer_email', 'string', ['notnull' => false, 'length' => 128]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['channel_id'], 'IDX_221CAD2F72F5A1AA', []);
        /** End of generate table eltrino_amazon_order **/

        /** Generate table eltrino_amazon_order_items **/
        $table = $schema->createTable('eltrino_amazon_order_items');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('order_id', 'integer', ['notnull' => false]);
        $table->addColumn('asin', 'string', ['length' => 60]);
        $table->addColumn('seller_sku', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('gift_message_text', 'string', ['notnull' => false, 'length' => 2048]);
        $table->addColumn('gift_price_currency_id', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('gift_price_amount', 'float', ['notnull' => false]);
        $table->addColumn('gift_level', 'string', ['notnull' => false, 'length' => 256]);
        $table->addColumn('cod_fee_currency_id', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('cod_fee_amount', 'float', ['notnull' => false]);
        $table->addColumn('cod_fee_discount_currency_id', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('cod_fee_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('order_item_id', 'string', ['notnull' => false, 'length' => 60]);
        $table->addColumn('title', 'string', ['notnull' => false, 'length' => 80]);
        $table->addColumn('quantity_ordered', 'integer', ['notnull' => false]);
        $table->addColumn('quantity_shipped', 'integer', ['notnull' => false]);
        $table->addColumn('item_price_currency_id', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('item_price_amount', 'float', ['notnull' => false]);
        $table->addColumn('item_condition', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('shipping_price_currency_id', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('shipping_price_amount', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['order_id'], 'IDX_346D93BC8D9F6D38', []);
        /** End of generate table eltrino_amazon_order_items **/

        $this->addEltrinoAmazonOrderForeignKeys($schema);
        $this->addEltrinoAmazonOrderItemsForeignKeys($schema);
    }

    /**
     * Add eltrino_amazon_order foreign keys.
     *
     * @param Schema $schema
     */
    protected function addEltrinoAmazonOrderForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('eltrino_amazon_order');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_channel'),
            ['channel_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * Add eltrino_amazon_order_items foreign keys.
     *
     * @param Schema $schema
     */
    protected function addEltrinoAmazonOrderItemsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('eltrino_amazon_order_items');
        $table->addForeignKeyConstraint(
            $schema->getTable('eltrino_amazon_order'),
            ['order_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
} 