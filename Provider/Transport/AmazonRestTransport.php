<?php

namespace OroCRM\Bundle\AmazonBundle\Provider\Transport;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use OroCRM\Bundle\AmazonBundle\Client\AuthHandler;
use OroCRM\Bundle\AmazonBundle\Client\Filters\CompositeFilter;
use OroCRM\Bundle\AmazonBundle\Client\Filters\FiltersFactory;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;
use OroCRM\Bundle\AmazonBundle\Client\RestClientFactory;
use OroCRM\Bundle\AmazonBundle\Provider\Iterator\OrderIterator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Amazon REST transport
 * used to fetch and pull data to/from Amazon instance
 * with sessionId param using REST requests
 *
 * @package OroCRM\Bundle\AmazonBundle
 */
class AmazonRestTransport implements TransportInterface
{
    /** @var ParameterBag */
    protected $settings;

    /** @var RestClient */
    protected $amazonClient;

    /**
     * @var CompositeFilter
     */
    protected $compositeFilter;

    /**
     * @var FiltersFactory
     */
    protected $filtersFactory;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $action;

    /**
     * @var AuthHandler
     */
    protected $authHandler;

    /**
     * @var int
     */
    protected $restoreRate = 0;

    /**
     * @var int
     */
    protected $maxRequestQuote = 0;

    /**
     * @var int
     */
    protected $callQty;

    public function __construct(FiltersFactory $filtersFactory)
    {
        $this->filtersFactory = $filtersFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'eltrino.amazon.transport.rest.label';
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
        $this->settings          = $transportEntity->getSettingsBag();
        $amazonRestClientFactory = new RestClientFactory();
        $this->amazonClient      = $amazonRestClientFactory->create(
            $this->settings->get('wsdl_url'),
            $this->settings->get('aws_access_key_id'),
            $this->settings->get('aws_secret_access_key'),
            $this->settings->get('merchant_id'),
            $this->settings->get('marketplace_id')
        );
    }

    /**
     * @param string $action
     * @param array  $params
     * @return array|mixed
     * @throws RuntimeException
     */
    public function call($action, $params = [])
    {

    }

    /**
     * @return RestClient
     */
    public function getAmazonClient()
    {
        return $this->amazonClient;
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
