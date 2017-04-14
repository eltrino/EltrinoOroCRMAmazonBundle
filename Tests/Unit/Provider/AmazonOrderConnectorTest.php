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

namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\Provider;

use Eltrino\OroCrmAmazonBundle\Provider\AmazonOrderConnector;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class AmazonOrderConnectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Oro\Bundle\ImportExportBundle\Context\ContextRegistry
     * @Mock Oro\Bundle\ImportExportBundle\Context\ContextRegistry
     */
    private $contextRegistry;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator
     * @Mock Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator
     */
    private $contextMediator;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Logger\LoggerStrategy
     * @Mock Oro\Bundle\IntegrationBundle\Logger\LoggerStrategy
     */
    private $logger;

    private $amazonOrderConnector;

    public function setUp()
    {
        MockAnnotations::init($this);
        $this->amazonOrderConnector = new AmazonOrderConnector(
            $this->contextRegistry,
            $this->logger,
            $this->contextMediator
        );
    }

    public function testGetLabel()
    {
        $this->assertEquals('Order connector', $this->amazonOrderConnector->getLabel());
    }

    public function testGetImportJobName()
    {
        $this->assertEquals('amazon_order_import', $this->amazonOrderConnector->getImportJobName());
    }

    public function testGetImportEntityFQCN()
    {
        $this->assertEquals('Eltrino\OroCrmAmazonBundle\Entity\Order', $this->amazonOrderConnector->getImportEntityFQCN());
    }

    public function testGetType()
    {
        $this->assertEquals('order', $this->amazonOrderConnector->getType());
    }
}
