<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Amazon\Filters;

use OroCRM\Bundle\AmazonBundle\Client\Filters\CreateTimeRangeFilter;

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

        $parameters          = [];
        $processedParameters = $filter->process($parameters);

        $expectedFrom = clone $from;
        $expectedFrom->sub(new \DateInterval('PT3M'));

        $expectedFrom = $from->format('Y-m-d\TH:i:sO');
        $expectedTo   = $to->format('Y-m-d\TH:i:sO');

        $expected = [
            'CreatedAfter'  => $expectedFrom,
            'CreatedBefore' => $expectedTo
        ];

        $this->assertEquals($expected, $processedParameters);
    }
}
