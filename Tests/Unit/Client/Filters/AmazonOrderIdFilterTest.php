<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Amazon\Filters;

use OroCRM\Bundle\AmazonBundle\Client\Filters\AmazonOrderIdFilter;

class AmazonOrderIdFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $amazonOrderId = '1';

        $filter = new AmazonOrderIdFilter($amazonOrderId);

        $parameters          = [];
        $processedParameters = $filter->process($parameters);

        $expectedAmazonOrderId = $amazonOrderId;

        $expected = [
            'AmazonOrderId' => $expectedAmazonOrderId,
        ];

        $this->assertEquals($expected, $processedParameters);
    }
}
