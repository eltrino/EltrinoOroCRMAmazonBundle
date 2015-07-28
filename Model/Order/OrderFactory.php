<?php

namespace OroCRM\Bundle\AmazonBundle\Model\Order;

use Doctrine\Common\Collections\ArrayCollection;

use OroCRM\Bundle\AmazonBundle\Entity\Order;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItem;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemShippingInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemCodFeeInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemGiftInfo;

class OrderFactory
{
    /**
     * Create Order
     * @param \SimpleXMLElement $data
     * @return Order
     */
    public function createOrder(\SimpleXMLElement $data)
    {
        $amazonOrderId                = (string)$data->AmazonOrderId;
        $customerEmail                = (string)$data->BuyerEmail;
        $marketPlaceId                = (string)$data->MarketplaceId;
        $shipServiceLevel             = (string)$data->ShipServiceLevel;
        $shipmentServiceLevelCategory = (string)$data->ShipmentServiceLevelCategory;
        $salesChannel                 = (string)$data->SalesChannel;
        $orderType                    = (string)$data->OrderType;
        $fulfillmentChannel           = (string)$data->FulfillmentChannel;
        $orderStatus                  = (string)$data->OrderStatus;
        $numberOfItemsShipped         = (string)$data->NumberOfItemsShipped;
        $numberOfItemsUnshipped       = (string)$data->NumberOfItemsUnshipped;
        $paymentMethod                = (string)$data->PaymentMethod;
        $currencyId                   = (string)$data->OrderTotal->CurrencyCode;
        $totalAmount                  = (string)$data->OrderTotal->Amount;

        $shipping     = new Shipping(
            $shipServiceLevel,
            $shipmentServiceLevelCategory,
            $numberOfItemsShipped,
            $numberOfItemsUnshipped
        );
        $payment      = new Payment($paymentMethod, $currencyId, $totalAmount);
        $orderDetails = new OrderDetails(
            $salesChannel,
            $orderType,
            $fulfillmentChannel,
            $orderStatus,
            $payment,
            $shipping
        );

        $order = new Order($amazonOrderId, $customerEmail, $marketPlaceId, $orderDetails);

        return $this->processOrderItems($data->OrderItems, $order);
    }

    /**
     * @param array $items
     * @param Order $order
     * @return ArrayCollection
     */
    protected function processOrderItems($items, Order $order)
    {
        foreach ($items as $item) {
            $asin                     = (string)$item->ASIN;
            $orderItemId              = (string)$item->OrderItemId;
            $sellerSku                = (string)$item->SellerSKU;
            $title                    = (string)$item->Title;
            $quantityOrdered          = (string)$item->QuantityOrdered;
            $quantityShipped          = (string)$item->QuantityShipped;
            $itemPriceCurrencyId      = (string)$item->ItemPrice->CurrencyCode;
            $itemPriceAmount          = (string)$item->ItemPrice->Amount;
            $shippingPriceCurrencyId  = (string)$item->ShippingPrice->CurrencyCode;
            $shippingPriceAmount      = (string)$item->ShippingPrice->Amount;
            $codFeeCurrencyId         = (string)$item->CODFee->CurrencyCode;
            $codFeeAmount             = (string)$item->CODFee->Amount;
            $codFeeDiscountCurrencyId = (string)$item->CODFeeDiscount->CurrencyCode;
            $codFeeDiscountAmount     = (string)$item->CODFeeDiscount->Amount;
            $giftMessageText          = (string)$item->GiftMessageText;
            $giftWrapPriceCurrencyId  = (string)$item->GiftWrapPrice->CurrencyCode;
            $giftWrapPriceAmount      = (string)$item->GiftWrapPrice->Amount;
            $giftWrapLevel            = (string)$item->GiftWrapLevel;
            $condition                = (string)$item->ConditionId;

            $itemInfo = new ItemInfo(
                $orderItemId,
                $title,
                $quantityOrdered,
                $quantityShipped,
                $itemPriceCurrencyId,
                $itemPriceAmount,
                $condition
            );

            $itemShippingInfo = new ItemShippingInfo($shippingPriceCurrencyId, $shippingPriceAmount);

            $itemCodFeeInfo = new ItemCodFeeInfo(
                $codFeeCurrencyId,
                $codFeeAmount,
                $codFeeDiscountCurrencyId,
                $codFeeDiscountAmount
            );

            $itemGiftInfo = new ItemGiftInfo(
                $giftMessageText,
                $giftWrapPriceCurrencyId,
                $giftWrapPriceAmount,
                $giftWrapLevel
            );

            $orderItem = new OrderItem(
                $asin,
                $sellerSku,
                $itemInfo,
                $itemShippingInfo,
                $itemCodFeeInfo,
                $itemGiftInfo
            );

            $order->addOrderItem($orderItem);
        }

        return $order;
    }
}
