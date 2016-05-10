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
namespace Eltrino\OroCrmAmazonBundle\Provider\Iterator\Order;

use Eltrino\OroCrmAmazonBundle\Amazon\AbstractRestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\AmazonOrderIdFilter;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Eltrino\OroCrmAmazonBundle\Amazon\RestClientInterface;
use Eltrino\OroCrmAmazonBundle\Provider\Iterator\AbstractNextTokenLoader;

use Guzzle\Http\Message\Response;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class OrderLoader extends AbstractNextTokenLoader implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var Filter */
    protected $firstFilter;

    /** @var string */
    protected $namespace;

    /**
     * @param RestClientInterface $client
     * @param Filter     $firstFilter
     * @param string     $nameSpace
     */
    public function __construct(RestClientInterface $client, Filter $firstFilter, $nameSpace)
    {
        $this->firstFilter = $firstFilter;
        $this->namespace   = $nameSpace;
        parent::__construct($client);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFirstRequest()
    {
        return new Request(AbstractRestClient::LIST_ORDERS, $this->firstFilter);
    }

    /**
     * {@inheritdoc}
     */
    protected function getNextTokenRequest()
    {
        return $this->nextToken
            ? new Request(
                AbstractRestClient::LIST_ORDERS_BY_NEXT_TOKEN,
                null,
                [AbstractRestClient::NEXT_TOKEN_PARAM => $this->nextToken]
            )
            : null;
    }

    /**
     * {@inheritdoc}
     */
    protected function processResponse($action, Response $response)
    {
        $result = $response->xml()->children($this->namespace);

        if (empty($result)) {
            $result = $response->xml();
        }

        $root   = $action . 'Result';

        $this->nextToken = null;
        if ($nextToken = (string)$result->{$root}->{AbstractRestClient::NEXT_TOKEN_PARAM}) {
            $this->nextToken = $nextToken;
        }
        $ordersXml = $result->{$root}->Orders;

        return $this->extractOrders($ordersXml);
    }

    /**
     * @param \SimpleXMLElement $ordersXml
     * @return array
     */
    protected function extractOrders(\SimpleXMLElement $ordersXml)
    {
        $orders = [];

        if ($ordersXml->count() && $ordersXml->children()->count()) {
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
        if (null !== $this->logger) {
            $this->logger->info('Loading order items for order #' . $amazonOrderId);
        }
        $firstRequest  = new Request(AbstractRestClient::LIST_ORDER_ITEMS, new AmazonOrderIdFilter($amazonOrderId));
        $firstResponse = $this->client->sendRequest($firstRequest);
        $result        = $firstResponse->xml()->children($this->namespace);

        if (empty($result)) {
            $result = $firstResponse->xml();
        }

        $root          = AbstractRestClient::LIST_ORDER_ITEMS . 'Result';
        $itemsXml      = $result->{$root}->OrderItems;
        $items         = null !== $itemsXml ? $this->extractItems($itemsXml) : [];
        $nextToken     = (string)$result->{$root}->NextToken;
        $nextTokenRoot = AbstractRestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN . 'Result';

        while ($nextToken) {
            $request   = new Request(
                AbstractRestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN,
                [],
                [AbstractRestClient::NEXT_TOKEN_PARAM => $nextToken]
            );
            $response   = $this->client->sendRequest($request);
            $result     = $response->xml()->children($this->namespace);

            if (empty($result)) {
                $result = $response->xml();
            }

            $itemsXmlNT = $result->{$nextTokenRoot}->OrderItems;
            $items      = null !== $itemsXmlNT ? array_merge($items, $this->extractItems($itemsXmlNT)) : $items;
            $nextToken  = (string)$result->{$nextTokenRoot}->NextToken;
        }

        return $items;
    }

    /**
     * @param \SimpleXMLElement $itemsXml
     * @return array
     */
    protected function extractItems(\SimpleXMLElement $itemsXml)
    {
        $items = [];
        if ($itemsXml->count() && $itemsXml->children()->count()) {
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
