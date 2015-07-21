<?php

namespace OroCRM\Bundle\AmazonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Entity\Transport;

/**
 * Class AmazonRestTransport
 * @package OroCRM\Bundle\AmazonBundle\Entity
 * @ORM\Entity()
 */
class AmazonRestTransport extends Transport
{
    /**
     * @var string
     *
     * @ORM\Column(name="wsdl_url", type="string", length=255, nullable=false)
     */
    protected $wsdlUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="aws_access_key_id", type="string", length=2048, nullable=false)
     */
    protected $keyId;

    /**
     * @var string
     *
     * @ORM\Column(name="aws_secret_access_key", type="string", length=255, nullable=false)
     */
    protected $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_id", type="string", length=255, nullable=false)
     */
    protected $merchantId;

    /**
     * @var string
     *
     * @ORM\Column(name="marketplace_id", type="string", length=255, nullable=false)
     */
    protected $marketplaceId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sync_start_date", type="date")
     */
    protected $syncStartDate;

    public function __construct()
    {
        $this->syncStartDate = new \DateTime('2007-01-01', new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     */
    public function getWsdlUrl()
    {
        return $this->wsdlUrl;
    }

    /**
     * @param string $wsdlUrl
     * @return $this
     */
    public function setWsdlUrl($wsdlUrl)
    {
        $this->wsdlUrl = $wsdlUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeyId()
    {
        return $this->keyId;
    }

    /**
     * @param $keyId
     * @return $this
     */
    public function setKeyId($keyId)
    {
        $this->keyId = $keyId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarketplaceId()
    {
        return $this->marketplaceId;
    }

    /**
     * @param $marketplaceId
     * @return $this
     */
    public function setMarketplaceId($marketplaceId)
    {
        $this->marketplaceId = $marketplaceId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param $merchantId
     * @return $this
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param $secret
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSyncStartDate()
    {
        return $this->syncStartDate;
    }

    /**
     * @param \DateTime $syncStartDate
     *
     * @return $this
     */
    public function setSyncStartDate(\DateTime $syncStartDate = null)
    {
        $this->syncStartDate = $syncStartDate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsBag()
    {
        return new ParameterBag(
            [
                'aws_access_key_id'     => $this->getKeyId(),
                'aws_secret_access_key' => $this->getSecret(),
                'merchant_id'           => $this->getMerchantId(),
                'marketplace_id'        => $this->getMarketplaceId(),
                'start_sync_date'       => $this->getSyncStartDate(),
                'wsdl_url'              => $this->getWsdlUrl(),
            ]
        );
    }
}
