<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    //Store Order in Database
    public function createOrder()
    {
        try {
            $order = new Order();
            $order->invoice_id = generateInvoiceId();
            $order->user_id = auth()->user()->id;
            $order->address = session()->get("address");
            $order->discount = session()->get("coupon")['discount'] ?? 0;
            $order->delivery_charge = session()->get('delivery_fee');
            $order->subtotal = cartTotal();
            $order->grand_total = grandCartTotal(session()->get('delivery_fee'));
            $order->product_qty = \Cart::content()->count();
            $order->payment_method = NULL;
            $order->payment_status = 'pending';
            $order->payment_approve_date = NULL;
            $order->transaction_id = NULL;
            $order->coupon_info = json_encode(session()->get('coupon')) ?? '';
            $order->currency_name = NULL;
            $order->order_status = 'pending';
            $order->address_id = session()->get('address_id');
            $order->save();

            foreach (\Cart::content() as $prod) //ky \ e lejon me e kqyr si class
            {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_name = $prod->name;
                $orderItem->product_id = $prod->id;
                $orderItem->unit_price = $prod->price;
                $orderItem->qty = $prod->qty;
                $orderItem->product_size = json_encode($prod->options->product_size);
                $orderItem->product_option = json_encode($prod->options->product_options);
                $orderItem->save();
            }

            //Putting grand total amount of order in session
            session()->put('order_id', $order->id);

            //Putting grand total amount of order in session
            session()->put('grand_total', $order->grand_total);



            return true;
        } catch (\Exception $e) {
            logger($e);
            return false;
        }
    }

    //Clear Session items
    public function clearSession()
    {
        \Cart::destroy();
        session()->forget('coupon');
        session()->forget('address');
        session()->forget('delivery_fee');
        session()->forget('delivery_area_id');
        session()->forget('order_id');
        session()->forget('grand_total');
    }
}
