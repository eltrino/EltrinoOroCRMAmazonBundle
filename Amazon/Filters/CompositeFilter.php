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
namespace Eltrino\OroCrmAmazonBundle\Amazon\Filters;

class CompositeFilter implements Filter
{
    /**
     * @var Filter[]
     */
    private $filters = array();

    /**
     * {@inheritdoc}
     */
    public function process(array $parameters)
    {
        foreach ($this->filters as $filter) {
            $parameters = $filter->process($parameters);
        }

        return $parameters;
    }

    /**
     * @param Filter $filter
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->filters = array();
    }
}
