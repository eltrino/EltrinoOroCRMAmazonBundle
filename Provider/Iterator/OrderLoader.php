<?php

namespace Eltrino\OroCrmAmazonBundle\Provider\Iterator;

use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\AmazonOrderIdFilter;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClient;
use Guzzle\Http\Message\Response;

class OrderLoader extends AbstractNextTokenLoader
{
    protected $firstFilter;

    protected $namespace;

    protected $nextToken;

    protected $filtersFactory;

    public function __construct(RestClient $client, Filter $firstFilter, $nameSpace)
    {
        $this->firstFilter = $firstFilter;
        $this->namespace = $nameSpace;
        parent::__construct($client);
    }
    protected function getFirstRequest()
    {
       return new Request(RestClient::LIST_ORDERS, $this->firstFilter);
    }

    protected function getNextTokenRequest()
    {
        return $this->nextToken
            ? null
            : new Request(
                RestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN,
                null,
                [RestClient::NEXT_TOKEN_PARAM => $this->nextToken]
            );
    }

    protected function processResponse($action, Response $response)
    {
        $result             = $response->xml()->children($this->namespace);
        $root = $action . 'Result';

        $this->nextToken = null;
        if ($nextToken = (string)$result->{$root}->{RestClient::NEXT_TOKEN_PARAM}) {
            $this->nextToken = $nextToken;
        }
        $ordersXml = $result->{$root}->Orders;

        return $this->extractOrders($ordersXml);
    }

    protected function extractOrders(\SimpleXMLElement $ordersXml)
    {
        $orders = [];

        if ($ordersXml->children()->count()) {
            foreach ($ordersXml->children() as $order) {
                $amazonOrderId = (string)$order->AmazonOrderId;
                if ($amazonOrderId) {
                    $items = $this->getOrderItems($amazonOrderId);
                    foreach ($items as $item) {
                        $this->appendSimpleXML($order->OrderItems[], $item);
                    }
                }
                $orders[] = $order;
            }
        }

        return $orders;
    }

    /**
     * @param string $amazonOrderId
     * @return array
     */
    protected function getOrderItems($amazonOrderId)
    {
        $firstRequest = new Request(RestClient::LIST_ORDER_ITEMS, new AmazonOrderIdFilter($amazonOrderId));
        $firstResponse = $this->client->sendRequest($firstRequest);
        $result             = $firstResponse->xml()->children($this->namespace);
        $root = RestClient::LIST_ORDER_ITEMS . 'Result';
        $items = $this->extractItems($result->{$root}->OrderItems);
        $nextToken = (string)$result->{$root}->NextToken;
        $nextTokenRoot = RestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN . 'Result';

        while ($nextToken) {
            $request = new Request(
                RestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN,
                [],
                [RestClient::NEXT_TOKEN_PARAM => $nextToken]
            );
            $response = $this->client->sendRequest($request);
            $result             = $response->xml()->children($this->namespace);
            $items = array_merge($items, $this->extractItems($result->{$nextTokenRoot}->OrderItems));
            $nextToken = (string)$result->{$nextTokenRoot}->NextToken;
        }

        return $items;
    }

    /**
     * @return array
     */
    protected function extractItems(\SimpleXMLElement $itemsXml)
    {
        $items = [];
        if ($itemsXml->children()->count()) {
            foreach ($itemsXml->children() as $item) {
                $items[] = $item;
            }
        }

        return $items;
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

            $this->appendSimpleXML($temp, $fromChild);
        }
    }
}
