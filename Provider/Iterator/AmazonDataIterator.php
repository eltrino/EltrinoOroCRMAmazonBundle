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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class AmazonDataIterator implements \Iterator, LoggerAwareInterface
{
    const LOAD_BATCH_SIZE = 1000;

    /** @var LoggerInterface */
    protected $logger;

    /** @var integer */
    protected $position = 0;

    /**
     * @var \SimpleXmlElement[]
     */
    protected $elements = [];

    /** @var NextTokenLoaderInterface */
    protected $loader;

    /** @var int */
    protected $loaded = 0;

    /** @var int */
    protected $batchSize = 0;

    /**
     * @param NextTokenLoaderInterface $loader
     * @param int                      $batchSize
     */
    public function __construct(NextTokenLoaderInterface $loader, $batchSize = self::LOAD_BATCH_SIZE)
    {
        $this->loader    = $loader;
        $this->batchSize = $batchSize;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $this->load();

        return isset($this->elements[$this->position]) ? $this->elements[$this->position] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $this->load();

        return isset($this->elements[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {$@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        if ($this->loader instanceof LoggerAwareInterface) {
            $this->loader->setLogger($logger);
        }
    }

    protected function load()
    {
        if ($this->shouldLoad()) {
            $this->loadElements();
        }
    }

    /**
     * @return bool
     */
    protected function shouldLoad()
    {
        return !isset($this->elements[$this->position]) && ($this->loader->getNextToken() || !$this->loader->isFirstRequestSend());
    }

    /**
     * @return array
     */
    protected function loadElements()
    {
        $elements = $this->loader->load($this->batchSize);
        $loaded   = count($elements);
        $start    = $this->loaded;
        $this->loaded += $loaded;
        $end            = $loaded ? $start + $loaded - 1 : false;
        $this->elements = $end !== false ? array_combine(range($start, $end), $elements) : [];
    }
}
