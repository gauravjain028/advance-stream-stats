<?php

namespace App\Http\Controllers;

use App\Services\Gateways\Braintree;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected Braintree $braintree;

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
        $plans = $this->braintree->plan()->all();

        $subscriptions = [];
        if (!empty($user->customer_id)) {
            $customer = $this->braintree->customer()->find($user->customer_id);
            foreach ($customer->paymentMethods as $paymentMethod) {
                $subscriptions = array_merge($subscriptions, $paymentMethod->subscriptions);
            }
        } else {
            $result = $this->braintree->customer()->create([
                'firstName' => $user->name,
                'email' => $user->email,
            ]);
            $user->customer_id = $result->customer->id;
            $user->save();
        }

        $clientToken = $this->braintree->clientToken()->generate([
            "customerId" => $user->customer_id,
        ]);

        return view('dashboard', [
            'user'  => $user,
            'plans' => $plans,
            'clientToken' => $clientToken,
            'subscriptions' => $subscriptions,
        ]);
    }
}
