<?php
/**
 * Created by PhpStorm.
 * User: ajax
 * Date: 8/13/15
 * Time: 7:27 PM
 */

namespace Eltrino\OroCrmAmazonBundle\Amazon;

use Eltrino\OroCrmAmazonBundle\Amazon\Client\Request;

abstract class AbstractRestClient implements RestClientInterface
{
    const GET_SERVICE_STATUS             = 'GetServiceStatus';
    const LIST_ORDERS                    = 'ListOrders';
    const LIST_ORDER_ITEMS               = 'ListOrderItems';
    const LIST_ORDERS_BY_NEXT_TOKEN      = 'ListOrdersByNextToken';
    const LIST_ORDER_ITEMS_BY_NEXT_TOKEN = 'ListOrderItemsByNextToken';

    const ACTION_PARAM     = 'Action';
    const NEXT_TOKEN_PARAM = 'NextToken';

    const STATUS_GREEN = 'GREEN';

    /**
     * {@inheritdoc}
     */
    abstract public function sendRequest(Request $request);

    /**
     * {@inheritdoc}
     */
    abstract public function getVersion();
}
