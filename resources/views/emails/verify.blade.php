@component('mail::message')
    # Email Verification

    Thank you for signing up.
    Your six-digit code is <h4>{{$pin}}</h4>

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
