<?php

namespace OroCRM\Bundle\AmazonBundle\Provider\Iterator;

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

    protected function load()
    {
        if (!$this->loaded) {
            $this->elements = $this->loadOrders();
        }
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        $this->load();

        return isset($this->elements[$this->position]) ? $this->elements[$this->position] : null;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        $this->load();

        return isset($this->elements[$this->position]);
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return array
     */
    protected function loadOrders()
    {
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
        $responses = $this->amazonClient->requestAction(RestClient::LIST_ORDERS_ACTION, $compositeFilter);
        $orders = $this->extractResultElements($responses, 'Orders');
        $this->loadOrderItems($orders);
        $this->loaded = true;
        return $orders;
    }

    /**
     * @param array $orders
     */
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
                    $this->appendSimpleXML($order->OrderItems[], $item);
                }
            }
        }
    }

    /**
     * @param \SimpleXMLElement $to
     * @param \SimpleXMLElement $from
     */
    protected function appendSimpleXML(\SimpleXMLElement &$to, \SimpleXMLElement $from)
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
        $responses = $this->amazonClient->requestAction(RestClient::LIST_ORDERS_ITEMS_ACTION, $filter);
        return $this->extractResultElements($responses, 'OrderItems');
    }

    /**
     * @param array  $responses
     * @param string $elementsName
     * @return array
     */
    protected function extractResultElements(array $responses, $elementsName)
    {
        $elements = [];
        /** @var \SimpleXMLElement $element */
        foreach ($responses as $response) {
            $element    = $response['result']->{$response['result_root']}->{$elementsName};
            if ($element->children()->count()) {
                foreach ($element->children() as $order) {
                    $elements[] = $order;
                }
            }
        }

        return $elements;
    }
}
