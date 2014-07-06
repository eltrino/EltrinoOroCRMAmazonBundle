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
namespace Eltrino\OroCrmAmazonBundle\Tests\Provider\Iterator;

use Eltrino\OroCrmAmazonBundle\Provider\Iterator\AmazonDataIterator;
use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

class AmazonDataIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AmazonDataIterator
     */
    private $iterator;

    /**
     * @var Loader
     * @Mock Eltrino\OroCrmAmazonBundle\Provider\Iterator\Loader
     */
    private $loader;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->iterator = new AmazonDataIterator($this->loader);
    }

    public function testKey()
    {
        $this->assertEquals(0, $this->iterator->key());
        $this->iterator->next();
        $this->iterator->next();
        $this->assertEquals(2, $this->iterator->key());
    }

    public function testNext()
    {
        $this->assertEquals(0, $this->iterator->key());
        $this->iterator->next();
        $this->assertEquals(1, $this->iterator->key());
    }

    public function testRewind()
    {
        $this->iterator->next();
        $this->iterator->next();
        $this->assertEquals(2, $this->iterator->key());
        $this->iterator->rewind();
        $this->assertEquals(0, $this->iterator->key());
    }

    public function testValidWhenElementsArrayIsEmpty()
    {
        $this->loader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array()));

        $this->assertFalse($this->iterator->valid());
    }

    public function testValid()
    {
        $elements = [
            new \SimpleXMLElement('<order><id>1</id></order>'),
            new \SimpleXMLElement('<order><id>2</id></order>')
        ];

        $this->loader
            ->expects($this->at(0))
            ->method('load')
            ->will($this->returnValue($elements));

        $this->loader
            ->expects($this->at(1))
            ->method('load')
            ->will($this->returnValue(array()));

        $this->assertTrue($this->iterator->valid());
        $this->iterator->next();
        $this->assertTrue($this->iterator->valid());
        $this->iterator->next();
        $this->assertFalse($this->iterator->valid());
    }

    public function testCurrentWhenElementsArrayIsEmpty()
    {
        $this->loader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array()));

        $this->assertNull($this->iterator->current());
    }

    public function testCurrent()
    {
        $elements1 = [
            new \SimpleXMLElement('<order><id>1</id></order>'),
            new \SimpleXMLElement('<order><id>2</id></order>')
        ];

        $elements2 = [
            new \SimpleXMLElement('<order><id>3</id></order>'),
            new \SimpleXMLElement('<order><id>4</id></order>')
        ];

        $this->loader
            ->expects($this->at(0))
            ->method('load')
            ->will($this->returnValue($elements1));

        $this->loader
            ->expects($this->at(1))
            ->method('load')
            ->will($this->returnValue($elements2));

        $this->loader
            ->expects($this->at(2))
            ->method('load')
            ->will($this->returnValue(array()));

        $elm1 = $this->iterator->current();
        $this->iterator->next();
        $elm2 = $this->iterator->current();
        $this->iterator->next();
        $elm3 = $this->iterator->current();
        $this->iterator->next();
        $elm4 = $this->iterator->current();

        $this->assertInstanceOf('SimpleXmlElement', $elm1);
        $this->assertInstanceOf('SimpleXmlElement', $elm2);

        $this->assertNotEquals($elm1, $elm2);

        $this->assertNotNull($elm3);
        $this->assertNotNull($elm4);

        $this->iterator->next();
        $this->assertNull($this->iterator->current());
    }
}
