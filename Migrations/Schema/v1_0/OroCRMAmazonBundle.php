<?php

namespace OroCRM\Bundle\AmazonBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroCRMAmazonBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        self::AddColumnsToIntegrationTransportTable($schema);
        self::createAmazonOrderTable($schema);
        self::createAmazonOrderItemTable($schema);
        self::addAmazonOrderForeignKeys($schema);
        self::addAmazonOrderItemsForeignKeys($schema);
    }

    /**
     * @param Schema $schema
     * @return \Doctrine\DBAL\Schema\Table
     */
    public static function createAmazonOrderTable(Schema $schema)
    {
        $table = $schema->createTable('orocrm_amazon_order');
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
    }

    /**
     * @param Schema $schema
     * @return \Doctrine\DBAL\Schema\Table
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public static function addColumnsToIntegrationTransportTable(Schema $schema)
    {
        $table = $schema->getTable('oro_integration_transport');
        if (!$table->hasColumn('wsdl_url')) {
            $table->addColumn('wsdl_url', 'string', ['length' => 255]);
        }
        if (!$table->hasColumn('sync_start_date')) {
            $table->addColumn('sync_start_date', 'date');
        }
        $table->addColumn('aws_access_key_id', 'string', ['length' => 2048]);
        $table->addColumn('aws_secret_access_key', 'string', ['length' => 255]);
        $table->addColumn('aws_merchant_id', 'string', ['length' => 255]);
        $table->addColumn('aws_marketplace_id', 'string', ['length' => 255]);
    }

    /**
     * @param Schema $schema
     */
    public static function addAmazonOrderForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('orocrm_amazon_order');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_channel'),
            ['channel_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * @param Schema $schema
     */
    public static function addAmazonOrderItemsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('orocrm_amazon_order_item');
        $table->addForeignKeyConstraint(
            $schema->getTable('orocrm_amazon_order'),
            ['order_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * @param Schema $schema
     */
    public static function createAmazonOrderItemTable(Schema $schema)
    {
        $table = $schema->createTable('orocrm_amazon_order_item');
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
    }
}
