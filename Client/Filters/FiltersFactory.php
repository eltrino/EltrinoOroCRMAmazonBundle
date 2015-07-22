<?php

namespace OroCRM\Bundle\AmazonBundle\Client\Filters;

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
     * @param string $amazonOrderId
     * @return AmazonOrderIdFilter
     */
    public function createAmazonOrderIdFilter($amazonOrderId)
    {
        return new AmazonOrderIdFilter($amazonOrderId);
    }
}
