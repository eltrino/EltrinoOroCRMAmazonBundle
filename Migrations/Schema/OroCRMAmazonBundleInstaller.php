<?php

namespace OroCRM\Bundle\AmazonBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use OroCRM\Bundle\AmazonBundle\Migrations\Schema\v1_0\OroCRMAmazonBundle;

class OroCRMAmazonBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        OroCRMAmazonBundle::AddColumnsToIntegrationTransportTable($schema);
        OroCRMAmazonBundle::createAmazonOrderTable($schema);
        OroCRMAmazonBundle::createAmazonOrderItemTable($schema);
        OroCRMAmazonBundle::addAmazonOrderForeignKeys($schema);
        OroCRMAmazonBundle::addAmazonOrderItemsForeignKeys($schema);
    }
}
