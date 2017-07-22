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
namespace Eltrino\OroCrmAmazonBundle\Model\Order;

/**
 * Class File, Value Object
 * @package Eltrino\OroCrmAmazonBundle\Model
 */
class Shipping
{
    /**
     * @var string
     */
    private $shipServiceLevel;

    /**
     * @var string
     */
    private $shipmentServiceLevelCategory;

    /**
     * @var string
     */
    private $numberOfItemsShipped;

    /**
     * @var string
     */
    private $numberOfItemsUnshipped;

    /**
     * @param string $shipServiceLevel
     * @param string $shipmentServiceLevelCategory
     * @param integer $numberOfItemsShipped
     * @param type $numberOfItemsUnshipped
     */
    public function __construct(
        $shipServiceLevel=null,
        $shipmentServiceLevelCategory=null, 
        $numberOfItemsShipped=null,
        $numberOfItemsUnshipped=null
    ) {
        $this->setShipServiceLevel($shipServiceLevel);
        $this->setShipmentServiceLevelCategory($shipmentServiceLevelCategory);
        $this->setNumberOfItemsShipped($numberOfItemsShipped);
        $this->setNumberOfItemsUnshipped($numberOfItemsUnshipped);
    }

    /**
     * @return string
     */
    public function getShipServiceLevel()
    {
        return $this->shipServiceLevel;
    }
    
    /**
     * @param string $shipServiceLevel
     * @return $this
     */
    public function setShipServiceLevel($shipServiceLevel)
    {
        $this->shipServiceLevel = $shipServiceLevel;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getShipmentServiceLevelCategory()
    {
        return $this->shipmentServiceLevelCategory;
    }
    
    /**
     * @param string $shipmentServiceLevelCategory
     * @return $this
     */
    public function setShipmentServiceLevelCategory($shipmentServiceLevelCategory)
    {
        $this->shipmentServiceLevelCategory = $shipmentServiceLevelCategory;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsShipped()
    {
        return $this->numberOfItemsShipped;
    }
    
    /**
     * @param integer $numberOfItemsShipped
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setNumberOfItemsShipped($numberOfItemsShipped)
    {
        if (!is_null($numberOfItemsShipped)) {
            if (!is_int($numberOfItemsShipped) && !ctype_digit($numberOfItemsShipped)) {
                throw new \InvalidArgumentException(sprintf(
                        "Expected integer value for numberOfItemsShipped. Received %s",
                        $numberOfItemsShipped
                    ));
            }
            $numberOfItemsShipped = (int)$numberOfItemsShipped;
        }
        $this->numberOfItemsShipped = $numberOfItemsShipped;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getNumberOfItemsUnshipped()
    {
        return $this->numberOfItemsUnshipped;
    }
    
    /**
     * @param integer $numberOfItemsUnshipped
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setNumberOfItemsUnshipped($numberOfItemsUnshipped)
    {
        if (!is_null($numberOfItemsUnshipped)) {
            if (!is_int($numberOfItemsUnshipped) && !ctype_digit($numberOfItemsUnshipped)) {
                throw new \InvalidArgumentException(sprintf(
                        "Expected integer value for numberOfItemsUnshipped. Received %s",
                        $numberOfItemsUnshipped
                    ));
            }
            $numberOfItemsUnshipped = (int)$numberOfItemsUnshipped;
        }
        $this->numberOfItemsUnshipped = $numberOfItemsUnshipped;
        
        return $this;
    }
}
