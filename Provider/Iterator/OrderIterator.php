<?php

namespace Eltrino\OroCrmAmazonBundle\Provider\Iterator;

use Eltrino\OroCrmAmazonBundle\Amazon\Client\Response;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\FiltersFactory;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class NextTokenIterator implements \Iterator, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const LOAD_BATCH_SIZE = 1000;

    /**
     * @var integer
     */
    protected $position = 0;

    /**
     * @var \SimpleXmlElement[]
     */
    protected $elements = [];

    protected $loader;

    protected $loaded = 0;

    public function __construct(AbstractNextTokenLoader $loader)
    {
        $this->loader = $loader;
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
        $elements = $this->loader->load(self::LOAD_BATCH_SIZE);
        $loaded = count($elements);
        $start = $this->loaded;
        $this->loaded += $loaded;
        $this->elements = array_combine(range($start, $start + $loaded - 1), $elements);
    }
}
