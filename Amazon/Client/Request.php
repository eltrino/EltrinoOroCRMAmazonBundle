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
