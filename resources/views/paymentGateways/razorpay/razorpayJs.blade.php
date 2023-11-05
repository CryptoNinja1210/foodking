@php $razorpayKey = ""; @endphp
@if(!blank($paymentGateways))
    @foreach($paymentGateways as $paymentGateway)
        @if($paymentGateway->slug === 'razorpay')
            @php $paymentGatewayOption = $paymentGateway->gatewayOptions->pluck('value', 'option'); $razorpayKey = $paymentGatewayOption['razorpay_key']; @endphp
        @endif
    @endforeach
@endif

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const razorpayKey = '<?=$razorpayKey?>';
    const razorpayTotalAmount = '<?=$order->total?>';
    const razorpayCurrencyCode = '<?=$currency->code?>';
    const razorpayCompany = '<?=$company['company_name']?>';
    const razorpayLogo = '<?=$logo->logo?>';
    const razorpayUserName = '<?= $order->user?->name?>';
    const razorpayUserEmail = '<?= $order->user?->email?>';
    const razorpayPayLink = '<?=route('payment.store', ['order' => $order])?>';
    const razorpaySuccessLink = '<?=route('payment.successful', ['order' => $order])?>';
    const razorpayCancelLink = '<?=route('payment.index', ['order' => $order])?>';
</script>
<script src="{{ asset('paymentGateways/razorpay/razorpay.js') }}"></script>
