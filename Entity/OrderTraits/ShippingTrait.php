<?php

namespace OroCRM\Bundle\AmazonBundle\Entity\OrderTraits;

use OroCRM\Bundle\AmazonBundle\Model\Order\Shipping;

trait ShippingTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="ship_service_level", type="string", length=300)
     */
    protected $shipServiceLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_service_level_category", type="string", length=300)
     */
    protected $shipmentServiceLevelCategory;

    /**
     * @var float
     *
     * @ORM\Column(name="number_of_items_shipped", type="integer", nullable=true)
     */
    protected $numberOfItemsShipped;

    /**
     * @var float
     *
     * @ORM\Column(name="number_of_items_unshipped", type="integer", nullable=true)
     */
    protected $numberOfItemsUnshipped;

    /**
     * @return Shipping
     */
    protected function initShipping()
    {
        return new Shipping(
            $this->shipServiceLevel,
            $this->shipmentServiceLevelCategory,
            $this->numberOfItemsShipped,
            $this->numberOfItemsUnshipped
        );
    }

    /**
     * @param Shipping $shipping
     */
    protected function initFromShipping(Shipping $shipping)
    {
        $this->shipServiceLevel             = $shipping->getShipServiceLevel();
        $this->shipmentServiceLevelCategory = $shipping->getShipmentServiceLevelCategory();
        $this->numberOfItemsShipped         = $shipping->getNumberOfItemsShipped();
        $this->numberOfItemsUnshipped       = $shipping->getNumberOfItemsUnshipped();
    }

    /**
     * @return string
     */
    public function getShipServiceLevel()
    {
        return $this->shipServiceLevel;
    }

    /**
     * @return string
     */
    public function getShipmentServiceLevelCategory()
    {
        return $this->shipmentServiceLevelCategory;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsShipped()
    {
        return $this->numberOfItemsShipped;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsUnshipped()
    {
        return $this->numberOfItemsUnshipped;
    }
}
