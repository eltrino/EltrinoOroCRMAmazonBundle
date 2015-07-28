<?php

namespace OroCRM\Bundle\AmazonBundle\Entity\OrderTraits;

use OroCRM\Bundle\AmazonBundle\Model\Order\OrderDetails;

trait OrderDetailsTrait
{
    use PaymentTrait;
    use ShippingTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="sales_channel", type="string", length=60, nullable=true)
     */
    protected $salesChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="order_type", type="string", length=60, nullable=true)
     */
    protected $orderType;

    /**
     * @var string
     *
     * @ORM\Column(name="fulfillment_channel", type="string", length=60, nullable=true)
     */
    protected $fulfillmentChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", length=60, nullable=true)
     */
    protected $orderStatus;

    /**
     * @param OrderDetails $orderDetails
     */
    protected function initFromOrderDetails(OrderDetails $orderDetails)
    {
        $this->salesChannel       = $orderDetails->getSalesChannel();
        $this->orderType          = $orderDetails->getOrderType();
        $this->fulfillmentChannel = $orderDetails->getFulfillmentChannel();
        $this->orderStatus        = $orderDetails->getOrderStatus();
    }

    /**
     * @return string
     */
    public function getSalesChannel()
    {
        return $this->salesChannel;
    }

    /**
     * @return string
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     * @return string
     */
    public function getFulfillmentChannel()
    {
        return $this->fulfillmentChannel;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }
}
