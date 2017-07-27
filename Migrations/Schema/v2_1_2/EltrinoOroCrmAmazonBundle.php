<?php

namespace Eltrino\OroCrmAmazonBundle\Migrations\Schema\v2_1_2;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Doctrine\DBAL\Types\Type;

class EltrinoOroCrmAmazonBundle implements Migration
{
    const ORDER_TABLE = 'eltrino_amazon_order';
    
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
        
        $table->addUniqueIndex(['amazon_order_id', 'marketplace_id'], 'unq_amznorder_amaznid_mrktplcid', []);
    }
}