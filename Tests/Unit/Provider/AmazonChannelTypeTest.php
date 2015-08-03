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

namespace Eltrino\OroCrmAmazonBundle\Tests\Unit\Provider;

use Eltrino\OroCrmAmazonBundle\Provider\AmazonChannelType;

class AmazonChannelTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var AmazonChannelType */
    private $channel;

    public function setUp()
    {
        $this->channel = new AmazonChannelType();
    }

    public function tearDown()
    {
        unset($this->channel);
    }

    public function testPublicInterface()
    {
        $this->assertEquals('Amazon', $this->channel->getLabel());
    }
}
