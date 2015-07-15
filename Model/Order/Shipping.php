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
     * @var string
     */
    protected $numberOfItemsShipped;

    /**
     * @var string
     */
    protected $numberOfItemsUnshipped;

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
