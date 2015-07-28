<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Unit\ImportExport\Strategy;

use OroCRM\Bundle\AmazonBundle\ImportExport\Strategy\OrderStrategy;
use Doctrine\Common\Collections\ArrayCollection;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class OrderStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper
     * @Mock Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper
     */
    private $strategyHelper;

    /**
     * @var \OroCRM\Bundle\AmazonBundle\Entity\Order
     * @Mock OroCRM\Bundle\AmazonBundle\Entity\Order
     */
    private $order;

    /**
     * @var \Doctrine\ORM\EntityManager
     * @Mock Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     * @Mock Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @var \Oro\Bundle\ImportExportBundle\Context\StepExecutionProxyContext
     * @Mock Oro\Bundle\ImportExportBundle\Context\StepExecutionProxyContext
     */
    private $context;

    /**
     * @var \Oro\Bundle\IntegrationBundle\Entity\Channel
     * @Mock Oro\Bundle\IntegrationBundle\Entity\Channel
     */
    private $channel;

    public function setUp()
    {
        MockAnnotations::init($this);
    }

    public function testProcessForCreate()
    {
        $this->order
            ->expects($this->once())
            ->method('getAmazonOrderId')
            ->will($this->returnValue(1));

        $this->order
            ->expects($this->once())
            ->method('getChannel')
            ->will($this->returnValue($this->channel));

        $this->strategyHelper
            ->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($this->em));

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));

        $this->order
            ->expects($this->exactly(3))
            ->method('getItems')
            ->will($this->returnValue(new ArrayCollection()));

        $strategy = new OrderStrategy($this->strategyHelper);
        $strategy->setImportExportContext($this->context);
        $strategy->process($this->order);
    }

    public function testProcessForUpdate()
    {
        $this->order
            ->expects($this->once())
            ->method('getAmazonOrderId')
            ->will($this->returnValue(1));

        $this->order
            ->expects($this->once())
            ->method('getChannel')
            ->will($this->returnValue($this->channel));

        $this->strategyHelper
            ->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($this->em));

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->order));

        $this->strategyHelper
            ->expects($this->once())
            ->method('importEntity')
            ->will($this->returnValue($this->em));

        $this->order
            ->expects($this->exactly(3))
            ->method('getItems')
            ->will($this->returnValue(new ArrayCollection()));

        $this->context
            ->expects($this->any())
            ->method('incrementUpdateCount');

        $strategy = new OrderStrategy($this->strategyHelper);
        $strategy->setImportExportContext($this->context);
        $strategy->process($this->order);
    }
}
