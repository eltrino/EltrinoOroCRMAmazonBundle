<?php

namespace OroCRMPackages\src\OroCRM\Bundle\AmazonBundle\Tests\Unit\Provider\Connector;

use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\IntegrationBundle\Logger\LoggerStrategy;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use OroCRM\Bundle\AmazonBundle\Provider\Connector\OrderConnector;

class OrderConnectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrderConnector
     */
    protected $object;

    protected function setUp()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|LoggerStrategy $logger */
        $logger       = $this
            ->getMockBuilder('Oro\Bundle\IntegrationBundle\Logger\LoggerStrategy')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \PHPUnit_Framework_MockObject_MockObject|ConnectorContextMediator $mediator */
        $mediator     = $this
            ->getMockBuilder('Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new OrderConnector(new ContextRegistry(), $logger, $mediator);
    }

    public function testGetLabel()
    {
        $this->assertEquals('Order connector', $this->object->getLabel());
    }

    public function testGetImportEntityFQCN()
    {
        $this->assertEquals(OrderConnector::ORDER_TYPE, $this->object->getImportEntityFQCN());
    }

    public function testGetImportJobName()
    {
        $this->assertEquals('amazon_order_import', $this->object->getImportJobName());
    }

    public function testGetType()
    {
        $this->assertEquals(OrderConnector::TYPE, $this->object->getType());
    }
}
