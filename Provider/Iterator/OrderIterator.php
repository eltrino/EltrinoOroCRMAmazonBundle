<?php

namespace OroCRM\Bundle\AmazonBundle\Provider\Iterator;

use OroCRM\Bundle\AmazonBundle\Client\Filters\FilterInterface;
use OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;

class OrderIterator implements \Iterator
{
    const INITIAL_MODE  = 'initial';
    const MODIFIED_MODE = 'modified';
    /**
     * @var RestClient
     */
    protected $amazonClient;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var \SimpleXmlElement[]
     */
    protected $elements = [];

    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * @param RestClient     $client
     * @param FiltersFactory $filtersFactory
     * @param \DateTime      $from
     * @param                $mode
     */
    public function __construct(RestClient $client, FiltersFactory $filtersFactory, \DateTime $from, $mode)
    {
        $this->amazonClient   = $client;
        $this->filtersFactory = $filtersFactory;
        $this->from           = $from;
        $this->mode           = $mode;
    }

    protected function load()
    {
        if ($this->shouldLoad()) {
            $this->elements = $this->loadOrders();
        }
    }

    /**
     * Check whether need to load extra elements
     * @return bool
     */
    protected function shouldLoad()
    {
        return empty($this->elements) || $this->position === count($this->elements);
    }

    /**
     * Return the current element
     * @return null|\SimpleXMLElement
     */
    public function current()
    {
        $this->load();

        return isset($this->elements[$this->position]) ? $this->elements[$this->position] : null;
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

    protected function loadOrders()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $now->sub(new \DateInterval('PT3M'));
        if ($this->mode === self::INITIAL_MODE) {
            $filter = $filter = $this
                ->filtersFactory
                ->createCreateTimeRangeFilter($this->from, $now);
        } else {
            $filter = $this
                ->filtersFactory
                ->createModTimeRangeFilter($this->from, $now);
        }
        $compositeFilter = $this->filtersFactory->createCompositeFilter();
        $compositeFilter->addFilter($filter);
        $responses = $this->amazonClient->makeRequest(RestClient::LIST_ORDERS_ACTION, $compositeFilter);
        $orders = $this->extractResultElements($responses, 'Orders');
        $this->loadOrderItems($orders);
        return $orders;
    }

    protected function loadOrderItems(array $orders)
    {
        $compositeFilter = $this->filtersFactory->createCompositeFilter();
        foreach ($orders as $order) {
            $amazonOrderId = (string)$order->AmazonOrderId;
            if ($amazonOrderId) {
                $amazonOrderIdFilter = $this->filtersFactory->createAmazonOrderIdFilter($amazonOrderId);
                $compositeFilter->reset();
                $compositeFilter->addFilter($amazonOrderIdFilter);

                $items = $this->getOrderItems($compositeFilter);

                foreach ($items as $item) {
                    $this->appendSimplexml($order->OrderItems[], $item);
                }
            }
        }
    }

    /**
     * @param $simplexmlTo
     * @param $simplexmlFrom
     */
    protected function appendSimplexml(&$simplexmlTo, &$simplexmlFrom)
    {
        foreach ($simplexmlFrom->children() as $simplexmlChild) {
            $simplexmlTemp = $simplexmlTo->addChild($simplexmlChild->getName(), htmlentities((string)$simplexmlChild));
            foreach ($simplexmlChild->attributes() as $attrKey => $attrValue) {
                $simplexmlTemp->addAttribute($attrKey, $attrValue);
            }

            $this->appendSimplexml($simplexmlTemp, $simplexmlChild);
        }
    }

    protected function getOrderItems(FilterInterface $filter)
    {
        $responses = $this->amazonClient->makeRequest(RestClient::LIST_ORDERS_ITEMS_ACTION, $filter);
        return $this->extractResultElements($responses, 'OrderItems');
    }

    protected function extractResultElements(array $responses, $elementsName)
    {
        $elements = [];

        /** @var \SimpleXMLElement $element */
        foreach ($responses as $response) {
            $element    = $response['result']->{$response['result_root']}->{$elementsName};
            if ($element->children()->count()) {
                $elements[] = $element->children();
            }
        }

        return $elements;
    }
}
