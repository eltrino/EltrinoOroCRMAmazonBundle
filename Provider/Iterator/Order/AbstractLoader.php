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
namespace OroCRM\Bundle\AmazonBundle\Provider\Iterator\Order;

use OroCRM\Bundle\AmazonBundle\Amazon\Filters\AmazonOrderIdFilter;
use OroCRM\Bundle\AmazonBundle\Amazon\Filters\CompositeFilter;
use OroCRM\Bundle\AmazonBundle\Amazon\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Amazon\Api\AmazonRestClient;
use OroCRM\Bundle\AmazonBundle\Amazon\Filters\ModTimeRangeFilter;
use OroCRM\Bundle\AmazonBundle\Provider\Iterator\Loader;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

abstract class AbstractLoader implements Loader
{
    /**
     * @var AmazonRestClient
     */
    protected $amazonRestClient;

    /**
     * @var FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var CompositeFilter
     */
    protected $compositeFilter;

    /**
     * @var \DateTime
     */
    protected $startSyncDate;

    /**
     * @var \DateTime
     */
    protected $now;

    public function __construct(
        AmazonRestClient $amazonRestClient,
        FiltersFactory $filtersFactory,
        \DateTime $startSyncDate = null
    ) {
        $this->amazonRestClient = $amazonRestClient;
        $this->filtersFactory = $filtersFactory;
        $this->startSyncDate = clone $startSyncDate;
        $this->now = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->compositeFilter = $this
            ->filtersFactory
            ->createCompositeFilter();
    }

    /**
     * @param $simplexmlTo
     * @param $simplexmlFrom
     */
    protected function appendSimplexml(&$simplexmlTo, &$simplexmlFrom)
    {
        foreach ($simplexmlFrom->children() as $simplexmlChild) {
            $simplexmlTemp = $simplexmlTo->addChild($simplexmlChild->getName(), htmlentities((string) $simplexmlChild));
            foreach ($simplexmlChild->attributes() as $attrKey => $attrValue) {
                $simplexmlTemp->addAttribute($attrKey, $attrValue);
            }

            $this->appendSimplexml($simplexmlTemp, $simplexmlChild);
        }
    }

    /**
     * @param \DateTime     $startSyncDate
     * @param \DateInterval $dateInterval
     *
     * @return array
     */
    protected function prepareDateRange(\DateTime $startSyncDate, \DateInterval $dateInterval)
    {
        $from = clone $startSyncDate;
        $to = clone $from;
        $to->add($dateInterval);
        return array($from, $to);
    }

    /**
     * @param array $orders
     */
    protected function loadOrderItems(array $orders)
    {
        foreach ($orders as $order) {
            $amazonOrderId = (string)$order->AmazonOrderId;
            if ($amazonOrderId) {
                $this->compositeFilter->reset();
                $this->compositeFilter->addFilter($this->createAmazonOrderIdFilter($amazonOrderId));

                $items = $this->amazonRestClient->getOrderItemsRestClient()->getOrderItems($this->compositeFilter);

                foreach ($items as $item) {
                    $this->appendSimplexml($order->OrderItems[], $item);
                }
            }
        }
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return mixed
     */
    protected function createCreateTimeFilter(\DateTime $from, \DateTime $to)
    {
        return $this
            ->filtersFactory
            ->createCreateTimeRangeFilter($from, $to);
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return ModTimeRangeFilter
     */
    protected function createModTimeFilter(\DateTime $from, \DateTime $to)
    {
        return $this
            ->filtersFactory
            ->createModTimeRangeFilter($from, $to);
    }

    /**
     * @param $amazonOrderId
     * @return AmazonOrderIdFilter
     */
    protected function createAmazonOrderIdFilter($amazonOrderId)
    {
        return $this
            ->filtersFactory
            ->createAmazonOrderIdFilter($amazonOrderId);
    }
}
