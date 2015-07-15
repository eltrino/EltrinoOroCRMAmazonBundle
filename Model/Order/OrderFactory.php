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

use OroCRM\Bundle\AmazonBundle\Entity\Order;
use OroCRM\Bundle\AmazonBundle\Entity\OrderItem;
use Doctrine\Common\Collections\ArrayCollection;
use SimpleXMLElement;

use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemShippingInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemCodFeeInfo;
use OroCRM\Bundle\AmazonBundle\Model\OrderItem\ItemGiftInfo;
class OrderFactory
{
    /**
     * Create Order
     * @param SimpleXMLElement $data
     * @return Order
     */
    public function createOrder(SimpleXMLElement $data)
    {
        $amazonOrderId                = (string) $data->AmazonOrderId;
        $customerEmail                = (string) $data->BuyerEmail;
        $marketPlaceId                = (string) $data->MarketplaceId;
        $shipServiceLevel             = (string) $data->ShipServiceLevel;
        $shipmentServiceLevelCategory = (string) $data->ShipmentServiceLevelCategory;
        $salesChannel                 = (string) $data->SalesChannel;
        $orderType                    = (string) $data->OrderType;
        $fulfillmentChannel           = (string) $data->FulfillmentChannel;
        $orderStatus                  = (string) $data->OrderStatus;
        $numberOfItemsShipped         = (string) $data->NumberOfItemsShipped;
        $numberOfItemsUnshipped       = (string) $data->NumberOfItemsUnshipped;
        $paymentMethod                = (string) $data->PaymentMethod;
        $currencyId                   = (string) $data->OrderTotal->CurrencyCode;
        $totalAmount                  = (string) $data->OrderTotal->Amount;

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
     * @param $items
     * @return ArrayCollection
     */
    protected function processOrderItems($items, Order $order)
    {
        foreach ($items as $item) {
            $asin                        = (string) $item->ASIN;
            $orderItemId                 = (string) $item->OrderItemId;
            $sellerSku                   = (string) $item->SellerSKU;
            $title                       = (string) $item->Title;
            $quantityOrdered             = (string) $item->QuantityOrdered;
            $quantityShipped             = (string) $item->QuantityShipped;
            $itemPriceCurrencyId         = (string) $item->ItemPrice->CurrencyCode;
            $itemPriceAmount             = (string) $item->ItemPrice->Amount;
            $shippingPriceCurrencyId     = (string) $item->ShippingPrice->CurrencyCode;
            $shippingPriceAmount         = (string) $item->ShippingPrice->Amount;
            $codFeeCurrencyId            = (string) $item->CODFee->CurrencyCode;
            $codFeeAmount                = (string) $item->CODFee->Amount;
            $codFeeDiscountCurrencyId    = (string) $item->CODFeeDiscount->CurrencyCode;
            $codFeeDiscountAmount        = (string) $item->CODFeeDiscount->Amount;
            $giftMessageText             = (string) $item->GiftMessageText;
            $giftWrapPriceCurrencyId     = (string) $item->GiftWrapPrice->CurrencyCode;
            $giftWrapPriceAmount         = (string) $item->GiftWrapPrice->Amount;
            $giftWrapLevel               = (string) $item->GiftWrapLevel;
            $condition                   = (string) $item->ConditionId;

            $itemInfo         = new ItemInfo(
                $orderItemId,
                $title,
                $quantityOrdered,
                $quantityShipped,
                $itemPriceCurrencyId,
                $itemPriceAmount,
                $condition
            );

            $itemShippingInfo = new ItemShippingInfo($shippingPriceCurrencyId, $shippingPriceAmount);

            $itemCodFeeInfo   = new ItemCodFeeInfo(
                $codFeeCurrencyId,
                $codFeeAmount,
                $codFeeDiscountCurrencyId,
                $codFeeDiscountAmount
            );

            $itemGiftInfo     = new ItemGiftInfo(
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
