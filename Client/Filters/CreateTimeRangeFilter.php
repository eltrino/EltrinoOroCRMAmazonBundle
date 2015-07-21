<?php

namespace OroCRM\Bundle\AmazonBundle\Client\Filters;

class CreateTimeRangeFilter extends AbstractTimeRangeFilter
{
    /**
     * @inheritdoc
     */
    public function process(array $parameters)
    {
        $parameters['CreatedAfter']  = $this->from->format(self::DATE_FORMAT);
        $parameters['CreatedBefore'] = $this->to->format(self::DATE_FORMAT);

        return $parameters;
    }
}
