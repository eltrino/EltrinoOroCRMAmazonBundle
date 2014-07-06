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
namespace Eltrino\OroCrmAmazonBundle\Provider\Iterator;

class AmazonDataIterator implements \Iterator
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array of \SimpleXmlElement's
     */
    private $elements = array();

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    private function load()
    {
        if ($this->shouldLoad()) {
            $elements = $this->loader->load();
            if ($elements) {
                $this->elements = array_merge($this->elements, $elements);
            }
        }
    }

    /**
     * Check whether need to load extra elements
     * @return bool
     */
    private function shouldLoad()
    {
        return empty($this->elements) || $this->position == count($this->elements);
    }

    /**
     * Return the current element
     * @return null|\SimpleXMLElement
     */
    public function current()
    {
        $this->load();
        return isset($this->elements[$this->position])?$this->elements[$this->position]:null;
    }

    /**
     * Move forward to next element
     * @return void
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Return the key of the current element
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @return boolean
     */
    public function valid()
    {
        $this->load();
        return isset($this->elements[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }
} 