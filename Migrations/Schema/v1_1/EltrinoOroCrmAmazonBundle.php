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

namespace Eltrino\OroCrmAmazonBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Doctrine\DBAL\Types\Type;

class EltrinoOroCrmAmazonBundle implements Migration
{
    const TABLE_NAME = 'eltrino_amazon_order';

    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        if ($schema->hasTable(self::TABLE_NAME)) {
            $this->alterEltrinoAmazonOrderTable($schema);
        }
    }

    /**
     * @param Schema $schema
     */
    public function alterEltrinoAmazonOrderTable(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE_NAME);

        if ($table->hasColumn('total_amount')) {
            $column = $table->getColumn('total_amount');
            $column->setType(Type::getType('money'));
            $column->setOptions(['default' => 0]);
        }
    }
} 
