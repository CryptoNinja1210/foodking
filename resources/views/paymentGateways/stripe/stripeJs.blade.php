@php $stripeKey = ""; @endphp
@if(!blank($paymentGateways))
    @foreach($paymentGateways as $paymentGateway)
        @if($paymentGateway->slug === 'stripe')
            @php $paymentGatewayOption = $paymentGateway->gatewayOptions->pluck('value', 'option'); $stripeKey =$paymentGatewayOption['stripe_key'] @endphp
        @endif
    @endforeach
@endif

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripeKey = '<?=$stripeKey?>';
</script>
<script src="{{ asset('paymentGateways/stripe/stripe.js') }}"></script>
