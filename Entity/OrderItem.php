<?php

namespace OroCRM\Bundle\AmazonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
 * @ORM\Table(name="orocrm_amazon_order_item")
 */
class OrderItem
{
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
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items", cascade={"persist"})
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

    /**
     * @param string           $asin
     * @param string           $sellerSku
     * @param ItemInfo         $itemInfo
     * @param ItemShippingInfo $itemShippingInfo
     * @param ItemCodFeeInfo   $itemCodFeeInfo
     * @param ItemGiftInfo     $itemGiftInfo
     */
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function assignOrder(Order $order = null)
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
