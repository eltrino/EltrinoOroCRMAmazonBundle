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
use Oro\Bundle\IntegrationBundle\Model\IntegrationEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\AddressBundle\Entity\AddressType;
use Oro\Bundle\AddressBundle\Entity\AbstractTypedAddress;

use Eltrino\OroCrmAmazonBundle\Entity\OrderTraits\OrderTrait;
use Eltrino\OroCrmAmazonBundle\Entity\OrderTraits\OrderDetailsTrait;
use Eltrino\OroCrmAmazonBundle\Entity\OrderTraits\TypedAddressesOwnerTrait;
use Eltrino\OroCrmAmazonBundle\Model\Order\OrderDetails;
use Eltrino\OroCrmAmazonBundle\Model\ExtendOrder;

/**
 * Class Order
 *
 * @package Eltrino\OroCrmAmazonBundle\Entity
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="eltrino_amazon_order",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unq_amznorder_amaznid_mrktplcid", columns={"amazon_order_id","marketplace_id"})
 *      }
 * )
 * @Config(
 *      routeName="eltrino_amazon_order_index",
 *      routeView="eltrino_amazon_order_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="fa-list-alt"
 *          },
 *          "merge"={
 *              "enable"=false
 *          }
 *      }
 * )
 */
class Order extends ExtendOrder
{
    use IntegrationEntityTrait;
    use OrderDetailsTrait;
    use OrderTrait;
    use TypedAddressesOwnerTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="amazon_order_id", type="string", length=60, nullable=false)
     */
    private $amazonOrderId;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_email", type="string", length=128, nullable=true)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="marketplace_id", type="string", length=60, nullable=true)
     */
    private $marketPlaceId;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
    
    /**
     * @var \DateTime $lastUpdateDate
     *
     * @ORM\Column(name="last_update_date", type="datetime")
     */
    private $lastUpdateDate;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     */
    private $items;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OrderAddress", mappedBy="owner", cascade={"all"}, orphanRemoval=true)
     */
    protected $addresses;

    /**
     * @var OrderDetails
     */
    private $orderDetails;

    /**
     * @param $amazonOrderId
     * @param $marketPlaceId
     * @param OrderDetails $orderDetails
     * @param \DateTime $createdAt - no longer used
     * @param \DateTime $lastUpdateDate
     */
    public function __construct(
        $amazonOrderId, 
        $customerEmail, 
        $marketPlaceId, 
        OrderDetails $orderDetails,
        \DateTime $createdAt = null,
        \DateTime $lastUpdateDate=null
    ) 
    {
        $this->setAmazonOrderId($amazonOrderId);
        $this->setCustomerEmail($customerEmail);
        $this->setMarketPlaceId($marketPlaceId);
        $this->setOrderDetails($orderDetails);

        $this->setLastUpdateDate($lastUpdateDate);

        $this->items = new ArrayCollection();
        $this->addresses = new ArrayCollection();

        $this->initFromShipping($orderDetails->getShipping());
        $this->initFromPayment($orderDetails->getPayment());
        $this->initFromOrderDetails($orderDetails);
    }
    
    /**
     * @return void
     */
    public function __clone()
    {
        if ($this->items) {
            $this->items = clone $this->items;
        }
        if ($this->addresses) {
            $this->addresses = clone $this->addresses;
        }
    }

    /**
     * @return string
     */
    public function getAmazonOrderId()
    {
        return $this->amazonOrderId;
    }
    
    /**
     * @param string $amazonOrderId
     * @return $this
     */
    public function setAmazonOrderId($amazonOrderId)
    {
        $this->amazonOrderId = $amazonOrderId;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }
    
    /**
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMarketPlaceId()
    {
        return $this->marketPlaceId;
    }
    
    /**
     * @param string $marketPlaceId
     * @return $this
     */
    public function setMarketPlaceId($marketPlaceId)
    {
        $this->marketPlaceId = $marketPlaceId;
        
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt=null)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt=null)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }
    
    /**
     * @param \DateTime $lastUpdateDate
     * @return $this
     */
    public function setLastUpdateDate(\DateTime $lastUpdateDate=null)
    {
        $this->lastUpdateDate = $lastUpdateDate;
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function addOrderItem(OrderItem $orderItem)
    {
        $orderItem->assignOrder($this);
        $this->items->add($orderItem);
    }

    /**
     * @return OrderDetails
     */
    public function getOrderDetails()
    {
        $this->initOrderDetails();
        return $this->orderDetails;
    }
    
    /**
     * @param OrderDetails $orderDetails
     * @return $this
     */
    public function setOrderDetails(OrderDetails $orderDetails)
    {
        $this->orderDetails = $orderDetails;
        
        return $this;
    }

    private function initOrderDetails()
    {
        if (is_null($this->orderDetails)) {
            $payment  = $this->initPayment(
                    $this->paymentMethod, 
                    $this->currencyId, 
                    $this->totalAmount
                );
            $shipping = $this->initShipping(
                    $this->shipServiceLevel, 
                    $this->shipmentServiceLevelCategory,
                    $this->numberOfItemsShipped, 
                    $this->numberOfItemsUnshipped
                );
            $this->orderDetails = new OrderDetails(
                    $this->salesChannel, 
                    $this->orderType, 
                    $this->fulfillmentChannel,
                    $this->orderStatus, 
                    $payment, 
                    $shipping
                );
        }
    }
    
    /**
     * @return void
     */
    public function beforeSave()
    {
        $dt = new \DateTime('now', new \DateTimeZone('UTC'));
        
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt($dt);
        }
        $this->setUpdatedAt($dt);
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->beforeSave();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->beforeSave();
    }
}
