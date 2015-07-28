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
        $this->action = $action;
        $this->filter = $filter;
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getFiltersParameters()
    {
        return (null !== $this->filter) ? $this->filter->process() : [];
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
