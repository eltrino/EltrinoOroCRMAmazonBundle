<?php

namespace OroCRM\Bundle\AmazonBundle\Model\Order;

/**
 * Class File, Value Object
 * @package OroCRM\Bundle\AmazonBundle\Model
 */
class Shipping
{
    /**
     * @var string
     */
    protected $shipServiceLevel;

    /**
     * @var string
     */
    protected $shipmentServiceLevelCategory;

    /**
     * @var integer
     */
    protected $numberOfItemsShipped;

    /**
     * @var integer
     */
    protected $numberOfItemsUnshipped;

    /**
     * @param string  $shipServiceLevel
     * @param string  $shipmentServiceLevelCategory
     * @param integer $numberOfItemsShipped
     * @param integer $numberOfItemsUnshipped
     */
    public function __construct(
        $shipServiceLevel,
        $shipmentServiceLevelCategory,
        $numberOfItemsShipped,
        $numberOfItemsUnshipped
    ) {
        $this->shipServiceLevel             = $shipServiceLevel;
        $this->shipmentServiceLevelCategory = $shipmentServiceLevelCategory;
        $this->numberOfItemsShipped         = $numberOfItemsShipped;
        $this->numberOfItemsUnshipped       = $numberOfItemsUnshipped;
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
     * @return integer
     */
    public function getNumberOfItemsShipped()
    {
        return $this->numberOfItemsShipped;
    }

    /**
     * @return integer
     */
    public function getNumberOfItemsUnshipped()
    {
        return $this->numberOfItemsUnshipped;
    }
}
