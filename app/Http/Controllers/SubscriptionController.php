<?php

namespace App\Http\Controllers;

use App\Services\Gateways\Braintree;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
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
     * Subscribe to a plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'nonce'  => 'required|string',
            'plan_id' => 'required|string',
        ]);

        $result = $this->braintree->subscribe(
            $request->input('nonce'),
            $request->input('plan_id'),
        );

        return redirect(route('dashboard'));
    }

    /**
     * Cancel the subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function cancel(Request $request, string $id)
    {
        $result = $this->braintree->cancelSubscription($id);

        return redirect(route('dashboard'));
    }
}
