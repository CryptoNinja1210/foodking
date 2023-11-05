@component('mail::message')
    # Order Notification

    Hello {{ $name }},

    Order ID : {{$orderId}}
    {{$message}}

    Thanks,
    {{ config('app.name') }}
@endcomponent
