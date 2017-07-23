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

use Eltrino\OroCrmAmazonBundle\Entity\OrderTraits\OrderTrait;
use Eltrino\OroCrmAmazonBundle\Entity\OrderTraits\OrderDetailsTrait;
use Eltrino\OroCrmAmazonBundle\Model\Order\OrderDetails;

/**
 * Class Order
 *
 * @package Eltrino\OroCrmAmazonBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="eltrino_amazon_order",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unq_amznorder_amaznid_mrktplcid", columns={"amazon_order_id","marketplace_id"})
 *      }
 * )
 */
class Order
{
    use IntegrationEntityTrait;
    use OrderDetailsTrait;
    use OrderTrait;

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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     */
    private $items;

    /**
     * @var OrderDetails
     */
    private $orderDetails;

    /**
     * @param $amazonOrderId
     * @param $marketPlaceId
     * @param OrderDetails $orderDetails
     * @param null $createdAt
     */
    public function __construct($amazonOrderId, $customerEmail, $marketPlaceId, OrderDetails $orderDetails,
                                $createdAt = null)
    {
        $this->amazonOrderId = $amazonOrderId;
        $this->customerEmail = $customerEmail;
        $this->marketPlaceId = $marketPlaceId;
        $this->orderDetails  = $orderDetails;
        $this->createdAt     = is_null($createdAt) ? new \DateTime('now') : $createdAt;

        $this->updatedAt = clone $this->createdAt;

        $this->items = new ArrayCollection();

        $this->initFromShipping($orderDetails->getShipping());
        $this->initFromPayment($orderDetails->getPayment());
        $this->initFromOrderDetails($orderDetails);
    }

    /**
     * @return string
     */
    public function getAmazonOrderId()
    {
        return $this->amazonOrderId;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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

    private function initOrderDetails()
    {
        if (is_null($this->orderDetails)) {
            $payment  = $this->initPayment($this->paymentMethod, $this->currencyId, $this->totalAmount);
            $shipping = $this->initShipping($this->shipServiceLevel, $this->shipmentServiceLevelCategory,
                $this->numberOfItemsShipped, $this->numberOfItemsUnshipped);

            $this->orderDetails = new OrderDetails($this->salesChannel, $this->orderType, $this->fulfillmentChannel,
                $this->orderStatus, $payment, $shipping);
        }
    }
}
