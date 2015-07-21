<?php

namespace OroCRM\Bundle\AmazonBundle\ImportExport\Strategy;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityRepository;

use Oro\Bundle\ImportExportBundle\Exception\InvalidArgumentException;
use Oro\Bundle\ImportExportBundle\Exception\LogicException;
use OroCRM\Bundle\AmazonBundle\Entity\Order;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItem;

use Oro\Bundle\ImportExportBundle\Context\ContextAwareInterface;
use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\ImportExportBundle\Strategy\Import\ImportStrategyHelper;
use Oro\Bundle\ImportExportBundle\Strategy\StrategyInterface;

class OrderStrategy implements StrategyInterface, ContextAwareInterface
{
    /** @var ImportStrategyHelper */
    protected $strategyHelper;

    /** @var ContextInterface */
    protected $context;

    /**
     * @param ImportStrategyHelper $strategyHelper
     */
    public function __construct(ImportStrategyHelper $strategyHelper)
    {
        $this->strategyHelper = $strategyHelper;
    }

    /**
     * @param mixed $importedOrder
     * @return mixed|null|object
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function process($importedOrder)
    {
        /** @var Order $importedOrder */
        $criteria = [
            'amazonOrderId' => $importedOrder->getAmazonOrderId(),
            'channel'       => $importedOrder->getChannel()
        ];
        $order    = $this->getEntityByCriteria($criteria, $importedOrder);

        if ($order) {
            $this->strategyHelper->importEntity($order, $importedOrder, ['id', 'amazonOrderId', 'items']);
        } else {
            $order = $importedOrder;
        }

        $this->processItems($order, $importedOrder);

        // check errors, update context increments
        return $this->validateAndUpdateContext($order);
    }

    /**
     * @param Order $entityToUpdate
     * @param Order $entityToImport
     */
    protected function processItems(Order $entityToUpdate, Order $entityToImport)
    {
        $importedOriginIds = $entityToImport->getItems()->map(
            function (OrderItem $item) {
                return $item->getOrderItemId();
            }
        )->toArray();

        // insert new and update existing items
        /** @var OrderItem $item - imported order item */
        foreach ($entityToImport->getItems() as $item) {
            $originId = $item->getOrderItemId();

            $existingItem = $entityToUpdate->getItems()->filter(
                function (OrderItem $item) use ($originId) {
                    return $item->getOrderItemId() == $originId;
                }
            )->first();

            if ($existingItem) {
                $this->strategyHelper->importEntity($existingItem, $item, ['id', 'order']);
                $item = $existingItem;
            }

            if (!$item->getOrder()) {
                $item->setOrder($entityToUpdate);
            }

            if (!$entityToUpdate->getItems()->contains($item)) {
                $entityToUpdate->getItems()->add($item);
            }
        }

        // delete order items that not exists in remote order
        $deleted = $entityToUpdate->getItems()->filter(
            function (OrderItem $item) use ($importedOriginIds) {
                return !in_array($item->getOrderItemId(), $importedOriginIds);
            }
        );
        foreach ($deleted as $item) {
            $entityToUpdate->getItems()->remove($item);
        }
    }

    /**
     * @param ContextInterface $context
     */
    public function setImportExportContext(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param array         $criteria
     * @param object|string $entity object to get class from or class name
     *
     * @return object
     */
    protected function getEntityByCriteria(array $criteria, $entity)
    {
        if (is_object($entity)) {
            $entityClass = ClassUtils::getClass($entity);
        } else {
            $entityClass = $entity;
        }

        return $this->getEntityRepository($entityClass)->findOneBy($criteria);
    }

    /**
     * @param string $entityName
     *
     * @return EntityRepository
     */
    protected function getEntityRepository($entityName)
    {
        return $this->strategyHelper->getEntityManager($entityName)->getRepository($entityName);
    }

    /**
     * @param object $entity
     *
     * @return null|object
     */
    protected function validateAndUpdateContext($entity)
    {
        // validate entity
        $validationErrors = $this->strategyHelper->validateEntity($entity);
        if ($validationErrors) {
            $this->context->incrementErrorEntriesCount();
            $this->strategyHelper->addValidationErrors($validationErrors, $this->context);

            return null;
        }

        // increment context counter
        if ($entity->getId()) {
            $this->context->incrementUpdateCount();
        } else {
            $this->context->incrementAddCount();
        }

        return $entity;
    }
}
