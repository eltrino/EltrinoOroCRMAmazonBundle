<?php

namespace Eltrino\OroCrmAmazonBundle\Amazon\Client;

use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;

class Request
{
    protected $filter;

    protected $action;

    protected $parameters;

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
     * @return mixed
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
