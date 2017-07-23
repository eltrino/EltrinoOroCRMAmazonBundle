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

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        if ($schema->hasTable(self::ORDER_TABLE)) {
            $this->alterEltrinoAmazonOrderTable($schema);            
        }
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
}