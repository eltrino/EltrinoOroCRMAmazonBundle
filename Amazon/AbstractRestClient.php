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
