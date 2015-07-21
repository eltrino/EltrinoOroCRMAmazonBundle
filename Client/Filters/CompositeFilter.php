<?php

namespace OroCRM\Bundle\AmazonBundle\Client\Filters;

class CompositeFilter implements FilterInterface
{
    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * @inheritdoc
     */
    public function process(array $parameters)
    {
        foreach ($this->filters as $filter) {
            $parameters = $filter->process($parameters);
        }

        return $parameters;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->filters = [];
    }
}
