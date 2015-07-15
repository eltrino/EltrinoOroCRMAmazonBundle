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
namespace OroCRM\Bundle\AmazonBundle\Amazon\Filters;

class FiltersFactory
{
    /**
     * @return CompositeFilter
     */
    public function createCompositeFilter()
    {
        return new CompositeFilter();
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return CreateTimeRangeFilter
     */
    public function createCreateTimeRangeFilter(\DateTime $from, \DateTime $to)
    {
        return new CreateTimeRangeFilter($from, $to);
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return ModTimeRangeFilter
     */
    public function createModTimeRangeFilter(\DateTime $from, \DateTime $to)
    {
        return new ModTimeRangeFilter($from, $to);
    }

    /**
     * @param $amazonOrderId
     * @return AmazonOrderIdFilter
     */
    public function createAmazonOrderIdFilter($amazonOrderId)
    {
        return new AmazonOrderIdFilter($amazonOrderId);
    }
}
