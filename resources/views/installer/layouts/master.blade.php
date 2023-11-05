<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- REQUIRED META TAGS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONTS -->
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/fontawesome/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/public/public.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/rubik/rubik.css') }}">

    <!-- CUSTOM STYLE -->
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">

    <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title')
        | @endif {{ trans('installer.title') }}</title>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="bg-installer bg-no-repeat bg-cover bg-center">
<div id="step-group" class="w-screen h-screen  overflow-y-auto p-3 sm:p-10">
    <div id="steps" class="block w-[550px] mx-auto overflow-hidden rounded-xl shadow-paper p-8 bg-white">
        <h3 class="text-lg font-semibold capitalize text-center mb-7">@yield('title')</h3>
        @yield('container')
    </div>
</div>

<script src="{{ asset('themes/default/js/jquery-v3.2.1.min.js') }}"></script>
<script src="{{ asset('themes/default/js/jqueryScript.js') }}"></script>
<script src="{{ asset('themes/default/js/installer.js') }}"></script>
</body>
</html>
