<?php

namespace App\Services;

use App\Entity\Cart;

class StripeService
{
    private $privateKey;

    public function __construct() //changer la clé une fois en production !
    {
        $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
    }

    /**
     * @param Cart $cart
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentItent(Cart $cart)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        return \Stripe\PaymentIntent::create([
            'amount' => $cart->getTotal()*100,
            'currency'=>'eur',
            'payment_method_types'=>['card']
        ]);
    }

    public function paiement(
        $amount,
        $currency,
        $description,
        array $stripeParameter
    )
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        if(isset($stripeParameter['stripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }
        
        if($stripeParameter['stripeIntentStatus'] === 'succeeded'){
            //TODO
        } else {
            $payment_intent->cancel();
        }

        return $payment_intent;
    }

    public function stripe(array $stripeParameter, Cart $cart)
    {
        return $this->paiement(
            $cart->getTotal(),
            'eur',
            $cart->getOwner(),
            $stripeParameter
        );
    }
}