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

use Eltrino\OroCrmAmazonBundle\Entity\Order;
use Eltrino\OroCrmAmazonBundle\Entity\OrderItem;
use Doctrine\Common\Collections\ArrayCollection;
use SimpleXMLElement;

use Eltrino\OroCrmAmazonBundle\Model\OrderItem\ItemInfo;
use Eltrino\OroCrmAmazonBundle\Model\OrderItem\ItemShippingInfo;
use Eltrino\OroCrmAmazonBundle\Model\OrderItem\ItemCodFeeInfo;
use Eltrino\OroCrmAmazonBundle\Model\OrderItem\ItemGiftInfo;
class OrderFactory
{
    /**
     * Create Order
     * @param SimpleXMLElement $data
     * @return Order
     */
    public function createOrder(SimpleXMLElement $data)
    {
        $purchaseDate                 = (string) $data->PurchaseDate;
        $customerName                 = (string) $data->BuyerName;
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
        $paymentMethodDetail          = (string) $data->PaymentMethodDetails->PaymentMethodDetail;
        $currencyId                   = (string) $data->OrderTotal->CurrencyCode;
        $totalAmount                  = (string) $data->OrderTotal->Amount;
        $sellerOrderId                = (string) $data->SellerOrderId;
        $earliestShipDate             = (string) $data->EarliestShipDate;
        $latestShipDate               = (string) $data->LatestShipDate;
        $isPremiumOrder               = (string) $data->IsPremiumOrder;
        $isReplacementOrder           = (string) $data->IsReplacementOrder;
        $isBusinessOrder              = (string) $data->IsBusinessOrder;
        $isPrime                      = (string) $data->IsPrime;
        $lastUpdateDate               = (string) $data->LastUpdateDate;
        
        // Contingency for strict SQL checks in MySQL 5.7
        // See: https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-strict
        
        // Decimal
        foreach (['totalAmount'] as $k) {
            ${$k} = ('' === ${$k}) ? null : (float)${$k};
        }
        
        // Integer
        foreach (['numberOfItemsShipped', 'numberOfItemsUnshipped'] as $k) {
            ${$k} = ('' === ${$k}) ? null : (int)${$k};
        }
        
        // Boolean (words)
        foreach (['isPremiumOrder', 'isReplacementOrder', 'isBusinessOrder', 'isPrime'] as $k) {
            ${$k} = ('' === ${$k}) ? null : ('true' == ${$k});
        }
        
        // DateTime
        foreach (['purchaseDate', 'earliestShipDate', 'latestShipDate', 'lastUpdateDate'] as $k) {
            ${$k} = ('' === ${$k}) ? null : new \DateTime(${$k});
        }

        $shipping = new Shipping(
                $shipServiceLevel, 
                $shipmentServiceLevelCategory, 
                $numberOfItemsShipped, 
                $numberOfItemsUnshipped
            );
        $payment = new Payment(
                $paymentMethod, 
                $currencyId, 
                $totalAmount,
                $paymentMethodDetail
            );
        $orderDetails = new OrderDetails(
                $salesChannel, 
                $orderType, 
                $fulfillmentChannel, 
                $orderStatus, 
                $payment, 
                $shipping,
                $purchaseDate,
                $customerName,
                $sellerOrderId,
                $earliestShipDate,
                $latestShipDate,
                $isPremiumOrder,
                $isReplacementOrder,
                $isBusinessOrder,
                $isPrime
            );

        $order = new Order(
                $amazonOrderId, 
                $customerEmail, 
                $marketPlaceId, 
                $orderDetails,
                new \DateTime("now"),
                $lastUpdateDate
            );

        return $this->processOrderItems($data->OrderItems, $order);
    }

    /**
     * @param $items
     * @return ArrayCollection
     */
    private function processOrderItems($items, Order $order)
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
        
            // Contingency for strict SQL checks in MySQL 5.7
            // See: https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-strict

            // Decimal
            foreach (['itemPriceAmount', 'shippingPriceAmount', 'codFeeAmount', 'codFeeDiscountAmount', 'giftWrapPriceAmount'] as $k) {
                ${$k} = ('' === ${$k}) ? null : (float)${$k};
            }

            // Integer
            foreach (['quantityOrdered', 'quantityShipped'] as $k) {
                ${$k} = ('' === ${$k}) ? null : (int)${$k};
            }

            $itemInfo         = new ItemInfo($orderItemId, $title, $quantityOrdered, $quantityShipped,
                $itemPriceCurrencyId, $itemPriceAmount, $condition);

            $itemShippingInfo = new ItemShippingInfo($shippingPriceCurrencyId, $shippingPriceAmount);

            $itemCodFeeInfo   = new ItemCodFeeInfo($codFeeCurrencyId, $codFeeAmount, $codFeeDiscountCurrencyId,
                $codFeeDiscountAmount);

            $itemGiftInfo     = new ItemGiftInfo($giftMessageText, $giftWrapPriceCurrencyId, $giftWrapPriceAmount,
                $giftWrapLevel);

            $orderItem = new OrderItem($asin, $sellerSku, $itemInfo, $itemShippingInfo, $itemCodFeeInfo,
                $itemGiftInfo);

            $order->addOrderItem($orderItem);
        }

        return $order;
    }

}
