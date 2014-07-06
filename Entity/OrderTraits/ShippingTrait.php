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
namespace Eltrino\OroCrmAmazonBundle\Entity\OrderTraits;
use Eltrino\OroCrmAmazonBundle\Model\Order\Shipping;

trait ShippingTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="ship_service_level", type="string", length=300)
     */
    private $shipServiceLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_service_level_category", type="string", length=300)
     */
    private $shipmentServiceLevelCategory;

    /**
     * @var float
     *
     * @ORM\Column(name="number_of_items_shipped", type="integer", nullable=true)
     */
    private $numberOfItemsShipped;

    /**
     * @var float
     *
     * @ORM\Column(name="number_of_items_unshipped", type="integer", nullable=true)
     */
    private $numberOfItemsUnshipped;

    /**
     * @return Shipping
     */
    protected function initShipping()
    {
        return new Shipping($this->shipServiceLevel, $this->shipmentServiceLevelCategory,
            $this->numberOfItemsShipped, $this->numberOfItemsUnshipped);
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
}
