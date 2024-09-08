<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Climate\Order;

class PaymentsController extends Controller
{
    public function create(Order $order)
    {
        return view('front.payments.create', [
            'order' => $order,
        ]);
    }
    public function createStripePaymentIntent(Order $order)
    {
        $amount = $order->items->sum(function ($item){
            return $item->price*$item->quantity;
        });
        $stripe = new \Stripe\StripeClient(
            config('services.stripe.secret_key')
        );
       $paymentIntent= $stripe->paymentIntents->create([
            'amount' => $amount,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);
       return[ 'clientSecret'=>  $paymentIntent-> client_secret,
       ];
    }
    public function confirm(Request $request ,Order $order)
    {
        $stripe = new \Stripe\StripeClient(  config('services.stripe.secret_key'));
        $paymentIntent= $stripe->paymentIntents->retrieve($request->query('payment_intent'), []);
//       dd($payment_intent);
        if ($paymentIntent->status == 'succeeded') {
// Create payment

            $payment = new Payment();
            $payment->forceFill([
                'order_id' => $order->id,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'method'=>'stripe',
                'transaction_id' => $paymentIntent->id,
                'status' => 'completed',
                'transaction_data' => json_encode($paymentIntent),
])->save();
        event('payment.created',$payment->id);
        return redirect()->route('home',[
            "status" => 'payment-succeeded'
        ]);
        }
        return redirect()->route('orders.payments.create',[
            'order'=>$order->id,
            'status'=>'payement-failed'
        ]);
    }
}
