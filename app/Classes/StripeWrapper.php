<?php

namespace App\Classes;
/* Exceptions */



/* Models */
use App\Models\User;
use App\Http\Models\Customer;
use App\Http\Models\CustomerCardDetail;


class StripeWrapper
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }


    public static function generateToken($request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $token = $stripe->tokens->create(array(
            "card" => array(
                "number"    => $request->input('card_number'),
                "exp_month" => $request->input('exp_month'),
                "exp_year"  => $request->input('exp_year'),
                "cvc"       => $request->input('cvc'),
                "name"      => $request->input('name')
            )
        ));


        return $token;
    }

    public static function createCustomer($request, User $customer)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        // create customer
        $stripe = $stripe->customers->create([
            "email" => \Auth::user()->username,
            "name" => $request->input('name'),
        ]);

        $customer->stripe_user_id = $stripe->id;
        $customer->save();

        return $customer;
    }


    public function getCustomer($request)
    {
        try {
            $customer = $this->stripe->customers->retrieve(
                'cus_' . \Auth::user()->customer->user_id,
                []
            );
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $this->createCustomer($request,\Auth::user());
        }
    }

    public static function createCard($request, $customer)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $customer = ($customer->stripe_user_id) ? $customer :  self::createCustomer($request, $customer);

        $token =  self::generateToken($request);

        // create source
        $response = $stripe->customers->createSource(
            $customer->stripe_user_id,
            ['source' => $token['id']]
        );


        return $response->toArray(true);
    }

    public function charge($card_id, $amount, $description, $stripe_id = 0, $autoCharge = false)
    {
        $stripe_user_id = ($stripe_id) ? $stripe_id : \Auth::user()->customer->stripe_user_id;

        $data = $this->stripe->charges->create([
            "amount" => $amount * 100,
            "capture" => true,
            "currency" => "usd",
            'customer' => $stripe_user_id,
            "source" =>  $card_id,
            "description" => $description
        ]);

        return (object)$data->toArray(true);
    }

    public function capture($chargeId)
    {
        $data = $this->stripe->charges->capture(
            $chargeId,
            []
        );


        return $data->toArray(true);
    }


    public function deleteCard($card, $customer)
    {
        $data = $this->stripe->customers->deleteSource(
            $customer->stripe_user_id,
            $card->card_id,
            []
        );


        return $data->toArray(true);
    }

    /**
     *  Verify card is valid or not
     */
    public static function setupIntent($data, $customer)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $response = $stripe->setupIntents->create(
            [
                'payment_method' => $data['id'],
                'customer' => $customer->stripe_user_id,
                'payment_method_types' => ['card'],
                'confirm' => true
            ]
        );


        return (object)$response->toArray(true);
    }
}
