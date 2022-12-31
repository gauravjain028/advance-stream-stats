<?php

namespace App\Http\Controllers;

use App\Services\Gateways\Braintree;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected Braintree $braintree;

    public function __construct(Braintree $braintree)
    {
        $this->braintree = $braintree;    
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'nonce'  => 'required|string',
            'plan_id' => 'required|string',
        ]);

        $result = $this->braintree->subscription()->create([
            'paymentMethodNonce' => $request->input('nonce'),
            'planId' =>  $request->input('plan_id'),
        ]);

        return redirect(route('dashboard'));
    }

    public function cancel(Request $request)
    {
        $this->validate($request, [
            'id'  => 'required|string',
        ]);

        $result = $this->braintree->subscription()->cancel($request->input('id'));

        return redirect(route('dashboard'));
    }
}
