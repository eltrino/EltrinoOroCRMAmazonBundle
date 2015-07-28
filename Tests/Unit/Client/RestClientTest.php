<?php

namespace OroCRMPackages\src\OroCRM\Bundle\AmazonBundle\Tests\Unit\Client;

use Guzzle\Http\ClientInterface;
use OroCRM\Bundle\AmazonBundle\Client\AuthHandler;
use OroCRM\Bundle\AmazonBundle\Client\AuthHandlerInterface;
use OroCRM\Bundle\AmazonBundle\Client\RestClient;
use OroCRM\Bundle\AmazonBundle\Client\RestClientResponse;

class RestClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var RestClient */
    protected $object;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface */
    protected $client;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthHandlerInterface */
    protected $authHandler;

    public function setUp()
    {
        $this->client = $this->getMockBuilder('Guzzle\Http\ClientInterface')->getMock();
        $this->authHandler = $this->getMockBuilder('OroCRM\Bundle\AmazonBundle\Client\AuthHandlerInterface')->getMock();
        $this->object = new RestClient($this->client, $this->authHandler);
    }

    public function testRequestAction()
    {
        $xml = new \SimpleXMLElement(file_get_contents(__DIR__ . '/../Fixtures/OrderItemsResult.xml'));
        $itemsResponse = new RestClientResponse($xml->children(), 'ListOrderItemsResult');
        $itemsNextTokenResponse = new RestClientResponse($xml->children(), 'ListOrderItemsByNextTokenResult');
        $itemsResponse->setNextToken('MRgZW55IGNhcm5hbCBwbGVhc3VyZS6=');
        $listAction = RestClient::LIST_ORDER_ITEMS;
        $byNextTokenAction = RestClient::LIST_ORDER_ITEMS_BY_NEXT_TOKEN;
        $listParameters = $this->getRequestParameters($listAction);
        $byNextTokenParameters = $this->getRequestParameters($byNextTokenAction);

        $reflection = new \ReflectionClass($this->object);
        $requestsCounters = $reflection->getProperty('requestsCounters');
        $requestsCounters->setAccessible(true);

        $filter = $this
            ->getMockBuilder('OroCRM\Bundle\AmazonBundle\Client\Filters\FilterInterface')
            ->getMock();
        $filter
            ->expects($this->exactly(2))
            ->method('process')
            ->willReturn([]);

        $this->prepareAuthHandlerMock();
        $this->prepareClientMock($xml, $listParameters, $byNextTokenParameters);

        $this->assertEquals(
            $itemsResponse,
            $this->object->requestAction($listAction, $filter, $listParameters)
        );
        $this->assertEquals(
            $itemsNextTokenResponse,
            $this->object->requestAction($byNextTokenAction, $filter, $byNextTokenParameters)
        );

        $this->assertEquals(2, $requestsCounters->getValue($this->object)[$listAction]);
    }

    protected function prepareClientMock(\SimpleXMLElement $xml, array $listParameters, array $byNextTokenParameters)
    {
        $request = $this->getMockBuilder('Guzzle\Http\Message\EntityEnclosingRequestInterface')
            ->getMock();
        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response
            ->expects($this->exactly(2))
            ->method('xml')
            ->willReturn($xml);

        $request
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn($response);

        $this->client
            ->expects($this->any())
            ->method('getBaseUrl')
            ->willReturn('https://mws.amazonservices.com/Orders');

        $this->client
            ->expects($this->at(1))
            ->method('post')
            ->with(null, [], $listParameters)
            ->willReturn($request);

        $this->client
            ->expects($this->at(4))
            ->method('post')
            ->with(null, [], $byNextTokenParameters)
            ->willReturn($request);
    }

    protected function getRequestParameters($action)
    {
        return [
            RestClient::ACTION_PARAM => $action,
            'SellerId'           => 'SellerId',
            'MarketplaceId.Id.1' => 'MarketplaceId.Id.1',
            'AWSAccessKeyId'     => 'AWSAccessKeyId',
            'Timestamp'          => 'Timestamp',
            'Version'            => '2013-09-01',
            'SignatureVersion'   => 'SignatureVersion',
            'SignatureMethod'    => 'SignatureMethod',
            'Signature'          => 'Signature'
        ];
    }

    protected function prepareAuthHandlerMock()
    {
        $getters = [
            'SellerId'           => 'getSellerId',
            'MarketplaceId.Id.1' => 'getMarketplaceId',
            'AWSAccessKeyId'     => 'getKeyId',
            'Timestamp'          => 'getFormattedTimestamp',
            '2013-09-01'         => 'getVersion',
            'SignatureVersion'   => 'getSignatureVersion',
            'SignatureMethod'    => 'getSignatureMethod',
            'Signature'          => 'getSignature'
        ];
        foreach ($getters as $value => $getter) {
            $this->authHandler
                ->expects($this->any())
                ->method($getter)
                ->willReturn($value);
        }
    }
}
