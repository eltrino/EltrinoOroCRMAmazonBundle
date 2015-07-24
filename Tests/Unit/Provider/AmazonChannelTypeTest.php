<?php

namespace OroCRM\Bundle\AmazonBundle\Tests\Unit\Provider;

use OroCRM\Bundle\AmazonBundle\Provider\AmazonChannelType;

class AmazonChannelTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var AmazonChannelType */
    protected $object;

    public function setUp()
    {
        $this->object = new AmazonChannelType();
    }

    public function testPublicInterface()
    {
        $this->assertEquals('Amazon', $this->object->getLabel());
    }
}
