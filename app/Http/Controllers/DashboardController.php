<?php

namespace App\Http\Controllers;

use App\Services\Gateways\Braintree;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Reference to instance of Braintree Gateway.
     * 
     * @var \App\Services\Gateways\Braintree
     */
    protected Braintree $braintree;

    /**
     * Constructor
     * 
     * @param \App\Services\Gateways\Braintree $braintree
     */
    public function __construct(Braintree $braintree)
    {
        $this->braintree = $braintree;    
    }

    /**
     * Display the user's dashbaord.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all the plans from braintree.
        $plans = $this->braintree->getAllPlans();

        // Get the subscription is user's profile is already created on Braintree.
        $subscriptions = [];
        if (!empty($user->customer_id)) {
            $customer = $this->braintree->getCustomer($user->customer_id);
            foreach ($customer->paymentMethods as $paymentMethod) {
                $subscriptions = array_merge($subscriptions, $paymentMethod->subscriptions);
            }
        } else {
            // Create a profile on Braintree for the user.
            $result = $this->braintree->createCusotmer([
                'firstName' => $user->name,
                'email' => $user->email,
            ]);

            $user->customer_id = $result->customer->id;
            $user->save();
        }

        // Generate Client Token for drop in ui for checkout.
        $clientToken = $this->braintree->generateClientToken($user->customer_id);

        return view('dashboard', [
            'user'  => $user,
            'plans' => $plans,
            'clientToken' => $clientToken,
            'subscriptions' => $subscriptions,
        ]);
    }
}
