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

namespace Eltrino\OroCrmAmazonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Entity\Transport;

/**
 * Class AmazonRestTransport
 * @package Eltrino\OroCrmAmazonBundle\Entity
 * @ORM\Entity()
 */
class AmazonRestTransport extends Transport
{
    /**
     * @var string
     *
     * @ORM\Column(name="wsdl_url", type="string", length=255, nullable=false)
     */
    private $wsdlUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="aws_access_key_id", type="string", length=2048, nullable=false)
     */
    private $keyId;

    /**
     * @var string
     *
     * @ORM\Column(name="aws_secret_access_key", type="string", length=255, nullable=false)
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_id", type="string", length=255, nullable=false)
     */
    private $merchantId;

    /**
     * @var string
     *
     * @ORM\Column(name="marketplace_id", type="string", length=255, nullable=false)
     */
    private $marketplaceId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sync_start_date", type="date")
     */
    private $syncStartDate;

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
