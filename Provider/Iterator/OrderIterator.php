<?php

namespace OroCRM\Bundle\AmazonBundle\Provider\Iterator;

use OroCRM\Bundle\AmazonBundle\Client\RestClientResponse;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

use OroCRM\Bundle\AmazonBundle\Client\Filters\FilterInterface;
use OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;

class OrderIterator implements \Iterator, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const INITIAL_MODE  = 'initial';
    const MODIFIED_MODE = 'modified';
    const LOAD_BATCH_SIZE = 1000;

    /**
     * @var RestClient
     */
    protected $amazonClient;

    /**
     * @var integer
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
     * @var bool
     */
    protected $loaded = false;

    /**
     * @var bool
     */
    protected $firstRequestSend = false;
    /**
     * @var string
     */
    protected $nextToken;

    /**
     * @param RestClient     $client
     * @param FiltersFactory $filtersFactory
     * @param \DateTime      $from
     * @param string         $mode
     */
    public function __construct(RestClient $client, FiltersFactory $filtersFactory, \DateTime $from, $mode)
    {
        $this->amazonClient   = $client;
        $this->filtersFactory = $filtersFactory;
        $this->from           = $from;
        $this->mode           = $mode;
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
            $this->loadOrders();
        }
    }

    /**
     * @return bool
     */
    protected function shouldLoad()
    {
        return !isset($this->elements[$this->position]) && ($this->nextToken || !$this->firstRequestSend);
    }

    /**
     * @param int  $loaded
     * @param bool $clear
     */
    protected function loadBatch($loaded, $clear)
    {
        $max   = count($this->elements) ? max(array_keys($this->elements)) : false;
        $start = $max === false ? 0 : $max + 1;
        $this->logger->info(sprintf('Start loading orders from %d position', $start));
        $orders = [];
        while ($this->nextToken && ($loaded < self::LOAD_BATCH_SIZE)) {
            $response = $this->amazonClient->requestAction(
                RestClient::LIST_ORDERS_BY_NEXT_TOKEN,
                null,
                [RestClient::NEXT_TOKEN_PARAM => $this->nextToken]
            );
            $processed = $this->processOrdersResponse($start, $response);
            $orders += $processed;
            $countProcessed = count($processed);
            $loaded += $countProcessed;
            $start += $countProcessed;
        }
        if ($clear) {
            $this->elements = $orders;
        } else {
            $this->elements += $orders;
        }
    }

    /**
     * @param int                $start
     * @param RestClientResponse $response
     * @return array
     */
    protected function extractOrders($start, RestClientResponse $response)
    {
        $orders = [];
        $position = $start;
        /** @var \SimpleXMLElement $element */
        $element    = $response->getResult()->{$response->getResultRoot()}->Orders;
        if ($element->children()->count()) {
            foreach ($element->children() as $order) {
                $orders[$position] = $order;
                $position++;
            }
        }

        return $orders;
    }

    /**
     * @return array
     */
    protected function loadOrders()
    {
        $loaded = 0;
        $clear = true;
        if (!$this->firstRequestSend) {
            $this->amazonClient->setLogger($this->logger);
            $now = new \DateTime('now', new \DateTimeZone('UTC'));
            /**
             * Amazon mws api requirement:
             * Must be no later than two minutes before the time that the request was submitted.
             */
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
            $response = $this->amazonClient->requestAction(RestClient::LIST_ORDERS, $compositeFilter);
            $this->elements = $this->processOrdersResponse(0, $response);
            $this->firstRequestSend = true;
            $loaded = count($this->elements);
            $clear = false;
        }

        $this->loadBatch($loaded, $clear);
    }

    /**
     * @param                    $start
     * @param RestClientResponse $response
     * @return array
     */
    protected function processOrdersResponse($start, RestClientResponse $response)
    {
        $orders = $this->extractOrders($start, $response);
        $this->loadOrderItems($orders);
        if ($nextToken = $response->getNextToken()) {
            $this->nextToken = $nextToken;
        }

        return $orders;
    }

    /**
     * @param array $orders
     */
    protected function loadOrderItems(array $orders)
    {
        $compositeFilter = $this->filtersFactory->createCompositeFilter();
        /** @var \SimpleXmlElement $order */
        foreach ($orders as $key => $order) {
            $amazonOrderId = (string)$order->AmazonOrderId;
            if ($amazonOrderId) {
                $amazonOrderIdFilter = $this->filtersFactory->createAmazonOrderIdFilter($amazonOrderId);
                $compositeFilter->reset();
                $compositeFilter->addFilter($amazonOrderIdFilter);

                $items = $this->getOrderItems($compositeFilter);
                foreach ($items as $item) {
                    $this->appendSimpleXML($order->OrderItems[], $item);
                }
            }
        }
    }

    /**
     * @param \SimpleXMLElement $to
     * @param \SimpleXMLElement $from
     */
    protected function appendSimpleXML(\SimpleXMLElement &$to, \SimpleXMLElement &$from)
    {
        foreach ($from->children() as $fromChild) {
            $temp = $to->addChild($fromChild->getName(), htmlentities((string)$fromChild));
            foreach ($fromChild->attributes() as $attrKey => $attrValue) {
                $temp->addAttribute($attrKey, $attrValue);
            }

            $this->appendSimplexml($temp, $fromChild);
        }
    }

    /**
     * @param FilterInterface $filter
     * @return array
     */
    protected function getOrderItems(FilterInterface $filter)
    {
        $firstResponse = $this->amazonClient->requestAction(RestClient::LIST_ORDER_ITEMS, $filter);
        $nextToken = $firstResponse->getNextToken();
        $items = $this->extractItems($firstResponse);
        while ($nextToken) {
            $response = $this->amazonClient->requestAction(
                RestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN,
                null,
                [RestClient::NEXT_TOKEN_PARAM => $nextToken]
            );
            $items = array_merge($items, $this->extractItems($response));
        }
        return $items;
    }

    /**
     * @param RestClientResponse $response
     * @return array
     */
    protected function extractItems(RestClientResponse $response)
    {
        $items = [];
        /** @var \SimpleXMLElement $element */
        $element    = $response->getResult()->{$response->getResultRoot()}->OrderItems;
        if ($element->children()->count()) {
            foreach ($element->children() as $item) {
                $items[] = $item;
            }
        }

        return $items;
    }
}
