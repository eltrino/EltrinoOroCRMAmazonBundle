<?php

namespace OroCRM\Bundle\AmazonBundle\Client\Filters;

class ModTimeRangeFilter extends AbstractTimeRangeFilter
{
    /**
     * {@inheritdoc}
     */
    public function process(array $parameters = [])
    {
        $parameters['LastUpdatedAfter']  = $this->from->format(self::DATE_FORMAT);
        $parameters['LastUpdatedBefore'] = $this->to->format(self::DATE_FORMAT);

        return $parameters;
    }
}
