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

class CreateTimeRangeFilter implements Filter
{
    /**
     * Date Format ISO8601
     */
    const DATE_FORMAT = 'Y-m-d\TH:i:sO';

    /**
     * @var \DateTime
     */
    private $from;

    /**
     * @var \DateTime
     */
    private $to;

    public function __construct(\DateTime $from, \DateTime $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * {@inheritdoc}
     */
    public function process(array $parameters)
    {
        //'CreatedBefore' Must be no later than two minutes before the time that the request was submitted.
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        if ($this->to >= $now) {
            $this->to = $now->sub(new \DateInterval('PT3M'));
            $this->from = $this->from->sub(new \DateInterval('PT3M'));
        }

        $parameters['CreatedAfter']  = $this->from->format(self::DATE_FORMAT);
        $parameters['CreatedBefore'] = $this->to->format(self::DATE_FORMAT);

        return $parameters;
    }
}
