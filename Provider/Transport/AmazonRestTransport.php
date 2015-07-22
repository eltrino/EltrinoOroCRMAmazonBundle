<?php

namespace OroCRM\Bundle\AmazonBundle\Provider\Transport;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;

use OroCRM\Bundle\AmazonBundle\Client\AuthHandler;
use OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;
use OroCRM\Bundle\AmazonBundle\Client\RestClientFactory;
use OroCRM\Bundle\AmazonBundle\Provider\Iterator\OrderIterator;

class AmazonRestTransport implements TransportInterface
{
    /** @var RestClient */
    protected $amazonClient;

    /**
     * @var FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var AuthHandler
     */
    protected $authHandler;

    /**
     * @var RestClientFactory
     */
    protected $clientFactory;

    public function __construct(RestClientFactory $clientFactory, FiltersFactory $filtersFactory)
    {
        $this->clientFactory = $clientFactory;
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'orocrm.amazon.transport.rest.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsFormType()
    {
        return 'orocrm_amazon_rest_transport_setting_form_type';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsEntityFQCN()
    {
        return 'OroCRM\Bundle\AmazonBundle\Entity\AmazonRestTransport';
    }

    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
        $settings           = $transportEntity->getSettingsBag();
        $this->amazonClient = $this->clientFactory->create(
            $settings->get('wsdl_url'),
            $settings->get('aws_access_key_id'),
            $settings->get('aws_secret_access_key'),
            $settings->get('aws_merchant_id'),
            $settings->get('aws_marketplace_id')
        );
    }

    protected function getOrders(\DateTime $startSyncDate = null, $mode = OrderIterator::MODIFIED_MODE)
    {
        return new OrderIterator($this->amazonClient, $this->filtersFactory, $startSyncDate, $mode);
    }

    public function getStatus()
    {
        $status    = false;
        $filter    = $this->filtersFactory->createCompositeFilter();
        $responses = $this->amazonClient->makeRequest(RestClient::GET_SERVICE_STATUS_ACTION, $filter);
        if (isset($responses[0])) {
            $status = $this->getStatusFromResponse($responses[0]);
        }

        return $status;
    }

    /**
     * @param array $response
     * @return bool
     */
    protected function getStatusFromResponse(array $response)
    {
        return (string)$response['result']->{$response['result_root']}->Status === RestClient::STATUS_GREEN;
    }

    public function getModOrders(\DateTime $from)
    {
        return $this->getOrders($from);
    }

    public function getInitialOrders(\DateTime $from)
    {
        return $this->getOrders($from, OrderIterator::INITIAL_MODE);
    }
}
