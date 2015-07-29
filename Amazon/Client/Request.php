<?php

namespace Eltrino\OroCrmAmazonBundle\Amazon\Client;

use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;

class Request
{
    /** @var Filter */
    protected $filter;

    /** @var string */
    protected $action;

    /** @var array */
    protected $parameters;

    /**
     * @param string      $action
     * @param Filter|null $filter
     * @param array       $parameters
     */
    public function __construct($action, Filter $filter = null, array $parameters = [])
    {
        $this->action     = $action;
        $this->filter     = $filter;
        $this->parameters = $parameters;
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function processFiltersParameters(array $parameters = [])
    {
        return (null !== $this->filter) ? $this->filter->process($parameters) : [];
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
