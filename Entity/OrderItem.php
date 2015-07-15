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
namespace OroCRM\Bundle\AmazonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits\OrderItemTrait;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits\ItemGiftInfoTrait;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits\ItemCodFeeInfoTrait;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits\ItemInfoTrait;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItemTraits\ItemShippingInfoTrait;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemGiftInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemCodFeeInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemShippingInfo;

/**
 * Class OrderItem
 *
 * @package OroCRM\Bundle\AmazonBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="eltrino_amazon_order_items")
 */
class OrderItem
{
    use OrderItemTrait;
    use ItemGiftInfoTrait;
    use ItemCodFeeInfoTrait;
    use ItemInfoTrait;
    use ItemShippingInfoTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Order", cascade={"persist"})
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $order;

    /**
     * @var string
     *
     * @ORM\Column(name="asin", type="string", length=60)
     */
    protected $asin;

    /**
     * @var integer
     *
     * @ORM\Column(name="seller_sku", type="string", length=60, nullable=true)
     */
    protected $sellerSku;

    /**
     * @var ItemInfo
     */
    protected $itemInfo;

    /**
     * @var ItemShippingInfo
     */
    protected $itemShippingInfo;

    /**
     * @var ItemCodFeeInfo
     */
    protected $itemCodFeeInfo;

    /**
     * @var ItemGiftInfo
     */
    protected $itemGiftInfo;

    public function __construct(
        $asin,
        $sellerSku,
        ItemInfo $itemInfo,
        ItemShippingInfo $itemShippingInfo,
        ItemCodFeeInfo $itemCodFeeInfo,
        ItemGiftInfo $itemGiftInfo
    ) {
        $this->asin             = $asin;
        $this->sellerSku        = $sellerSku;
        $this->itemInfo         = $itemInfo;
        $this->itemShippingInfo = $itemShippingInfo;
        $this->itemCodFeeInfo   = $itemCodFeeInfo;
        $this->itemGiftInfo     = $itemGiftInfo;

        $this->initFromItemInfo($itemInfo);
        $this->initFromItemShippingInfo($itemShippingInfo);
        $this->initFromItemCodFeeInfo($itemCodFeeInfo);
        $this->initFromItemGiftInfo($itemGiftInfo);
    }

    /**
     * @return string
     */
    public function getBuyerEmail()
    {
        return $this->buyerEmail;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \OroCRM\Bundle\AmazonBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function assignOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * @return string
     */
    public function getSellerSku()
    {
        return $this->sellerSku;
    }
}
