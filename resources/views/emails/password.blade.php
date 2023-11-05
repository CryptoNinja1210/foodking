@component('mail::message')
    # Reset Password

    Your code is {{$pin}}

    Please do not share your One Time Code With Anyone.
    You made a request to reset your password. Please
    discard if this wasn't you.

    Thanks,
    {{ config('app.name') }}
@endcomponent
