<?php

namespace Eltrino\OroCrmAmazonBundle\Migrations\Schema\v2_2;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Doctrine\DBAL\Types\Type;

class EltrinoOroCrmAmazonBundle implements Migration
{
    const ORDER_TABLE = 'eltrino_amazon_order';
    const ORDER_ITEM_TABLE = 'eltrino_amazon_order_items';
    const ORDER_ADDRESS_TABLE = 'eltrino_amazon_order_addr';
    const ORDER_ADDRESS_TYPE_TABLE = 'eltrino_amazon_order_addr_type';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        if ($schema->hasTable(self::ORDER_TABLE)) {
            $this->alterEltrinoAmazonOrderTable($schema);            
        }
        if (!$schema->hasTable(self::ORDER_ADDRESS_TABLE)) {
            $this->createEltrinoAmazonOrderAddrTable($schema);
        }
        if (!$schema->hasTable(self::ORDER_ADDRESS_TYPE_TABLE)) {
            $this->createEltrinoAmazonOrderAddrTypeTable($schema);
        }
        
        $this->addEltrinoAmazonOrderAddrForeignKeys($schema);
        $this->addEltrinoAmazonOrderAddrTypeForeignKeys($schema);
    }
    
    /**
     * @param Schema $schema
     */
    public function alterEltrinoAmazonOrderTable(Schema $schema)
    {
        $table = $schema->getTable(self::ORDER_TABLE);
    
        $table->addColumn('last_update_date', 'datetime', ['notnull' => false]);
        $table->addColumn('purchase_date', 'datetime', ['notnull' => false]);
        $table->addColumn('customer_name', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('seller_order_id', 'string', ['length' => 60, 'notnull' => false]);
        $table->addColumn('earliest_ship_date', 'datetime', ['notnull' => false]);
        $table->addColumn('latest_ship_date', 'datetime', ['notnull' => false]);
        $table->addColumn('is_premium_order', 'boolean', ['notnull' => false]);
        $table->addColumn('is_replacement_order', 'boolean', ['notnull' => false]);
        $table->addColumn('is_business_order', 'boolean', ['notnull' => false]);
        $table->addColumn('is_prime', 'boolean', ['notnull' => false]);
        $table->addColumn('payment_method_detail', 'string', ['length' => 255, 'notnull' => false]);
        $table->addIndex(['last_update_date'], 'IDX_AMZNORDER_LASTUPDATE', []);
        $table->addIndex(['purchase_date'], 'IDX_AMZNORDER_PURCHASEDATE', []);
    }
    
    /**
     * @param Schema $schema
     */
    public function createEltrinoAmazonOrderAddrTable(Schema $schema)
    {
        $table = $schema->createTable(self::ORDER_ADDRESS_TABLE);
        
        $table->addColumn('id', 'integer', ['precision' => 0, 'autoincrement' => true]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('organization', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('street', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('street2', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('city', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('region_code', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('region_text', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('postal_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('country_code', 'string', ['notnull' => false, 'length' => 2]);
        $table->addColumn('country_text', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('is_primary', 'boolean', ['notnull' => false]);
        $table->addColumn('created', 'datetime', ['precision' => 0]);
        $table->addColumn('updated', 'datetime', ['precision' => 0]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['owner_id'], 'IDX_AMZNORDADDR_OWNER', []);
        $table->addIndex(['country_code'], 'IDX_AMZNORDADDR_COUNTRY', []);
        $table->addIndex(['region_code'], 'IDX_AMZNORDADDR_REGION', []);
    }
    
    /**
     * @param Schema $schema
     */
    public function createEltrinoAmazonOrderAddrTypeTable(Schema $schema)
    {
        $table = $schema->createTable(self::ORDER_ADDRESS_TYPE_TABLE);
        
        $table->addColumn('order_address_id', 'integer', []);
        $table->addColumn('type_name', 'string', ['length' => 16]);
        $table->setPrimaryKey(['order_address_id', 'type_name']);
        $table->addIndex(['order_address_id'], 'IDX_AMZNORDERADDRTYPE_ADDR', []);
        $table->addIndex(['type_name'], 'IDX_AMZNORDERADDRTYPE_TYPE', []);
    }
    
    /**
     * @param Schema $schema
     */
    public function addEltrinoAmazonOrderAddrForeignKeys(Schema $schema)
    {
        $table = $schema->getTable(self::ORDER_ADDRESS_TABLE);
        $table->addForeignKeyConstraint(
            $schema->getTable(self::ORDER_TABLE),
            ['owner_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_country'),
            ['country_code'],
            ['iso2_code'],
            []
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_region'),
            ['region_code'],
            ['combined_code'],
            []
        );
    }
    
    /**
     * @param Schema $schema
     */
    public function addEltrinoAmazonOrderAddrTypeForeignKeys(Schema $schema)
    {
        $table = $schema->getTable(self::ORDER_ADDRESS_TYPE_TABLE);
        $table->addForeignKeyConstraint(
            $schema->getTable(self::ORDER_ADDRESS_TABLE),
            ['order_address_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_AMZNORDERADDR_TYPE'
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_address_type'),
            ['type_name'],
            ['name'],
            []
        );
        
    }
}