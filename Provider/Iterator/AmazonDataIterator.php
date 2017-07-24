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
use Psr\Log\LoggerAwareTrait;

class AmazonDataIterator implements \Iterator, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const LOAD_BATCH_SIZE = 25;

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
        $this->logger->debug(sprintf(
                "[ELTAMZ] Loading elements with batch size: %s",
                $this->batchSize
            ));
        
        $elements = $this->loader->load($this->batchSize);
        $loaded   = count($elements);
        $start    = $this->loaded;
        $this->loaded += $loaded;
        $end            = $loaded ? $start + $loaded - 1 : false;
        
        $this->logger->debug(sprintf(
                "[ELTAMZ] Elements loaded: %d; Total loaded to date: %d; Start: %d; End: %s;",
                $loaded,
                $this->loaded,
                $start,
                ($end === false) ? 'FALSE' : $end
            ));
        $this->logger->debug(sprintf(
                "[ELTAMZ] Peak Memory Usage: %s MB",
                number_format(memory_get_peak_usage(true) / 1024 / 1024, 2)
            ));
        
        $this->elements = $end !== false ? array_combine(range($start, $end), $elements) : [];
    }
}
