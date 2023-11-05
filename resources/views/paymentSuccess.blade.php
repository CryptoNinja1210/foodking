<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company['company_name']  }}</title>
    <link rel="icon" href="{{ $faviconLogo->faviconLogo }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/lab/lab.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">
</head>
<body>

<div class="py-14 px-4 w-full max-w-2xl mx-auto flex flex-col items-center justify-center">
    <a href="{{ route('home') }}" class="w-36 mb-8">
        <img class="w-full" src="{{ $logo->logo }}" alt="logo">
    </a>

    <img class="w-full max-w-[120px] mb-3" src="{{ asset('images/default/payment-success.gif') }}" alt="success">

    <h3 class="text-[22px] font-medium leading-[34px] text-center text-[#1AB759] mb-12">
        <span class="block">{{ __('all.label.congratulations') }}</span>
        {{ __('all.message.payment_successful') }}
    </h3>
    <div class="w-full max-w-[360px]">
        <dl class="text-center shadow-xs w-full mb-8">
            <dt class="uppercase py-2.5 rounded-tl-lg rounded-tr-lg text-heading bg-[#F7F7FC]">{{ __('all.label.transaction_id')  }}</dt>
            <dd class="uppercase py-3 rounded-bl-lg rounded-br-lg payment-font-size font-medium leading-10 text-heading bg-white">{{ $order?->transaction?->transaction_no }}</dd>
        </dl>
        <a href="{{ route('home') }}"
           class="py-3 w-full rounded-3xl text-center text-base font-medium bg-primary text-white">{{ __('all.label.back_to_home') }}</a>
    </div>
</div>

</body>
</html>
