<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="payment-form" action="{{route('subscription.subscribe')}}" method="post">
                        @csrf
                        <div class="flex flex-wrap -m-4 text-center">
                            <h2 class="text-gray-900 text-lg font-bold text-left">PLANS</h2>
                            @foreach ($plans as $plan)
                                <div class="p-4 sm:w-1/2 lg:w-1/3 w-full ">
                                    <div class=" flex items-center  justify-between p-4  rounded-lg bg-white shadow-indigo-50 shadow-md">
                                        <div>
                                            <input type="radio" required name="plan_id" value="{{$plan->id}}">
                                            <h2 class="text-gray-900 text-lg font-bold text-left">{{$plan->name}}</h2>
                                            <h3 class="mt-2 text-xl font-bold text-green-500 text-left">{{$plan->price}} $</h3>
                                            <p class="text-sm font-semibold text-gray-400">{{$plan->description}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Putting the empty container you plan to pass to
                        `braintree.dropin.create` inside a form will make layout and flow
                        easier to manage -->
                        <div id="checkout-message"></div>
                        <div id="dropin-container"></div>
                        <input type="submit" />
                        <input type="hidden" id="nonce" name="nonce"/>
                    </form>
                </div>

                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap -m-4 text-center">
                        <h2 class="text-gray-900 text-lg font-bold text-left">Subscriptions</h2>
                        @foreach ($subscriptions as $subscription)
                            <form id="subscription-form-{{$subscription->id}}" action="{{route('subscription.cancel', $subscription->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <div class="p-4 sm:w-1/2 lg:w-1/3 w-full ">
                                    <div class=" flex items-center  justify-between p-4  rounded-lg bg-white shadow-indigo-50 shadow-md">
                                        <div>
                                            <h2 class="text-gray-900 text-lg font-bold text-left">{{$subscription->id}}</h2>
                                            <h3 class="mt-2 text-xl font-bold text-green-500 text-left">{{$subscription->price}} $</h3>
                                            <p class="text-sm font-semibold text-gray-400">{{$subscription->status}}</p>
                                        </div>
                                    </div>
                                </div>
                                @if (strtolower($subscription->status) === 'active') 
                                    <button type="submit">Cancel</button>
                                @endif
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('payment-form');

        braintree.dropin.create({
            authorization: '{{$clientToken}}',
            container: '#dropin-container',
            paypal: {
                flow: 'vault'
            }
        }, (error, dropinInstance) => {
            if (error) console.error(error);

            form.addEventListener('submit', event => {
                event.preventDefault();
                dropinInstance.requestPaymentMethod((error, payload) => {
                if (error) console.error(error);

                // Step four: when the user is ready to complete their
                //   transaction, use the dropinInstance to get a payment
                //   method nonce for the user's selected payment method, then add
                //   it a the hidden field before submitting the complete form to
                //   a server-side integration
                document.getElementById('nonce').value = payload.nonce;
                form.submit();
                });
            });
        });
    </script>
</x-app-layout>
