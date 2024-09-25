<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function stripe(Request $request){
$stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));

$response=$stripe->checkout->sessions->create([
  'line_items' => [
    [
      'price_data' => [
        'currency' => 'usd',
        'product_data' => ['name' => $request->product_name],
        'unit_amount' => $request->price*100,
      ],
      'quantity' => $request->quantity,
    ],
  ],
  'mode' => 'payment',
  'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
  'cancel_url' => route('cancel'),
]);
//dd($response);
if(isset($response->id) && $response->id !=''){
    session()->put('product_name',$request->product_name);
    session()->put('quantity',$request->quantity);
    session()->put('price',$request->price);
    return redirect($response->url);
}
    else{
        return redirect()->route('cancel');
    }
    }
    public function success(){
        return "Success";
    }
    public function cancel(){
        return "cancel";
    }
}
