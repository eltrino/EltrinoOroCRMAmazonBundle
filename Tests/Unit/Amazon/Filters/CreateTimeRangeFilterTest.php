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
namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\Amazon\Filters;

use Eltrino\OroCrmAmazonBundle\Amazon\Filters\CreateTimeRangeFilter;

class CreateTimeRangeFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $from = new \DateTime('now', new \DateTimeZone('UTC'));
        $from->sub(new \DateInterval('PT3M'));
        $to = new \DateTime('now', new \DateTimeZone('UTC'));

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        if ($to >= $now) {
            $to = $now->sub(new \DateInterval('PT3M'));
        }

        $filter = new CreateTimeRangeFilter($from, $to);

        $parameters = array();
        $processedParameters = $filter->process($parameters);

        $expectedFrom = clone $from;
        $expectedFrom->sub(new \DateInterval('PT3M'));

        $expectedFrom = $from->format('Y-m-d\TH:i:sO');
        $expectedTo   = $to->format('Y-m-d\TH:i:sO');

        $expected = array(
            'CreatedAfter'  => $expectedFrom,
            'CreatedBefore' => $expectedTo
        );

        $this->assertEquals($expected, $processedParameters);
    }
}
