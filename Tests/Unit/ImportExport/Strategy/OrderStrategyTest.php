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
namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\ImportExport\Srtategy;
use Eltrino\OroCrmAmazonBundle\ImportExport\Strategy\OrderStrategy;
use Doctrine\Common\Collections\ArrayCollection;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class OrderStrategyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper
     * @Mock Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper
     */
    private $strategyHelper;

    /**
     * @var \Eltrino\OroCrmAmazonBundle\Entity\Order
     * @Mock Eltrino\OroCrmAmazonBundle\Entity\Order
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
