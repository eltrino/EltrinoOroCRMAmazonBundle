<?php

namespace OroCRM\Bundle\AmazonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\IntegrationBundle\Model\IntegrationEntityTrait;

use OroCRM\Bundle\AmazonBundle\Entity\OrderTraits\OrderDetailsTrait;
use OroCRM\Bundle\AmazonBundle\Model\Order\OrderDetails;

/**
 * Class Order
 *
 * @package OroCRM\Bundle\AmazonBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="orocrm_amazon_order")
 */
class Order
{
    use IntegrationEntityTrait;
    use OrderDetailsTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="amazon_order_id", type="string", length=60, nullable=false)
     */
    protected $amazonOrderId;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_email", type="string", length=128, nullable=true)
     */
    protected $customerEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="marketplace_id", type="string", length=60, nullable=true)
     */
    protected $marketPlaceId;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var OrderItem[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"all"})
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $items;

    /**
     * @var OrderDetails
     */
    protected $orderDetails;

    /**
     * @param string         $amazonOrderId
     * @param string         $customerEmail
     * @param string         $marketPlaceId
     * @param OrderDetails   $orderDetails
     * @param \DateTime|null $createdAt
     */
    public function __construct(
        $amazonOrderId,
        $customerEmail,
        $marketPlaceId,
        OrderDetails $orderDetails,
        \DateTime $createdAt = null
    ) {
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
     * @return ArrayCollection|OrderItem[]
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

    protected function initOrderDetails()
    {
        if (is_null($this->orderDetails)) {
            $payment  = $this->initPayment();
            $shipping = $this->initShipping();
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
}
