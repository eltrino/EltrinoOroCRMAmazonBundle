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

use Eltrino\OroCrmAmazonBundle\Amazon\Api\AuthorizationHandler;
use Eltrino\OroCrmAmazonBundle\Amazon\Api\CheckRestClient;
use Eltrino\OroCrmAmazonBundle\Amazon\Filters\Filter;
use Guzzle\Http\ClientInterface;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

class CheckRestClientImpl extends AbstractRestClientImpl implements CheckRestClient
{
    /**
     * @param ClientInterface $client
     * @param AuthorizationHandler $authHandler
     */
    public function __construct(ClientInterface $client, AuthorizationHandler $authHandler)
    {
        $this->action = 'GetServiceStatus';
        parent::__construct($client, $authHandler);
    }

    /**
     * @param Filter $filter
     * @return bool|mixed
     */
    public function getStatus(Filter $filter)
    {
        $response = $this->makeRequest($filter);
        return $this->getStatusFromResponse($response, 'c:ListOrderItemsResult/c:OrderItems/c:OrderItem');
    }

    /**
     * @param \SimpleXMLElement $response
     * @return bool
     */
    protected function getStatusFromResponse(\SimpleXMLElement $response)
    {
        $status = (string) $response->GetServiceStatusResult->Status;
        if ($status === 'GREEN') {
            return true;
        } else {
            return false;
        }
    }
}