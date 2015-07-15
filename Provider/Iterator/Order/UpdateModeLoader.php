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

use OroCRM\Bundle\AmazonBundle\Amazon\Filters\CompositeFilter;
use OroCRM\Bundle\AmazonBundle\Amazon\Filters\FiltersFactory;

class UpdateModeLoader extends AbstractLoader
{
    public function load()
    {
        $elements = array();

        while(empty($elements) && $this->startSyncDate < $this->now) {

            list($from, $to) = $this->prepareDateRange($this->startSyncDate, new \DateInterval('P2D'));
            $this->compositeFilter->reset();
            $this->compositeFilter->addFilter($this->createModTimeFilter($from, $to));

            $elements = $this->amazonRestClient->getOrderRestClient()
                ->getOrders($this->compositeFilter);

            $this->startSyncDate = clone $to;
        }

        $this->loadOrderItems($elements);
        return $elements;
    }
}
