<?php

declare(strict_types = 1);

namespace App\Services\Gateways;

use Braintree\Customer;
use Braintree\Gateway;
use Braintree\Result\Error;
use Braintree\Result\Successful;

class Braintree extends Gateway
{
    /*
     * Retrieve all plans
     *
     * @return array of Plan objects
     */
    public function getAllPlans() : array
    {
        return $this->plan()->all();
    }

    /**
     * find a customer by id
     *
     * @param string $id customer Id
     *
     * @throws Exception\NotFound
     *
     * @return \Braintree\Customer|bool The customer object or false if the request fails.
     */
    public function getCustomer($id) : Customer|bool
    {
        return $this->customer()->find($id);
    }

    /**
     * Creates a customer using the given +attributes+. If <tt>:id</tt> is not passed,
     * the gateway will generate it.
     *
     * @param array $attribs containing request parameters
     *
     * @return \Braintree\Result\Successful|\Braintree\Result\Error
     */
    public function createCusotmer(array $data) : Successful|Error
    {
        return $this->customer()->create($data);
    }

    /**
     * Generate a client token for client-side authorization
     *
     * @param $customerId
     *
     * @return string client token
     */
    public function generateClientToken($customerId)
    {
        return $this->clientToken()->generate([
            "customerId" => $customerId,
        ]);
    }

    /*
     * Request a new subscription be created
     *
     * @param array $attributes containing request params
     *
     * @return \Braintree\Result\Sucessful|\Braintree\Result\Error
     */
    public function subscribe(string $nonce, string $planId) : Successful|Error
    {
        return $this->subscription()->create([
            'paymentMethodNonce' => $nonce,
            'planId' =>  $planId,
        ]);
    }

    /*
     * Stops billing a payment method for a subscription. Cannot be reactivated
     *
     * @param string $subscriptionId to be canceled
     *
     * @return \Braintree\Result\Sucessful|\Braintree\Result\Error
     */
    public function cancelSubscription(string $id) : Successful|Error
    {
        return $this->subscription()->cancel($id);
    }
}
