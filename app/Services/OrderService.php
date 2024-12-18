<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Cart;

class OrderService
{

    function createOrder()
    {

        try {
            $order = new Order();
            $order->invoice_id = generateInvoiceid();
            $order->user_id = auth()->user()->id;
            $order->address = session()->get('address');
            $order->discount = session()->get('coupon')['discount'];
            $order->delivery_charge = session()->get('delivery_fee');
            $order->subtotal = cartTotal();
            $order->grand_total = grandCartTotal(session()->get('delivery_fee'));
            $order->product_qty = \Cart::content()->count();
            $order->payment_method = null;
            $order->payment_status = 'pending';
            $order->payment_approve_date = null;
            $order->transaction_id = null;
            $order->coupon_info = json_encode(session()->get('coupon'));
            $order->currency_name = null;
            $order->order_status = 'pending';
            $order->save();

            foreach (\Cart::content() as $product) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_name = $product->name;
                $orderItem->product_id = $product->id;
                $orderItem->unit_price = $product->price;
                $orderItem->qty = $product->qty;
                $orderItem->product_size = json_encode($product->options->product_size);
                $orderItem->product_option = json_encode($product->options->product_options);
                $orderItem->save();
            }

            return true;
        } catch (\Exception $e) {
            logger($e);
            return false;
        }
    }

    function clearSession() {}
}
