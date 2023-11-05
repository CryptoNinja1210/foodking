@extends('installer.layouts.master')

@section('template_title')
    {{ trans('installer.welcome.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer.welcome.title') }}
@endsection

@section('container')
    <ul class="installer-track">
        <li class="active"><i class="fa-solid fa-house"></i></li>
        <li><i class="fa-solid fa-server"></i></li>
        <li><i class="fa-sharp fa-solid fa-unlock"></i></li>
        <li><i class="fa-solid fa-key"></i></li>
        <li><i class="fa-solid fa-gear"></i></li>
        <li><i class="fa-solid fa-database"></i></li>
        <li><i class="fa-solid fa-unlock-keyhole"></i></li>
    </ul>

    <span class="my-6 w-full h-[1px] bg-[#EFF0F6]"></span>

    <div class="text-center">
        <h4 class="text-sm font-medium mb-7">{{ trans('installer.welcome.message') }}</h4>
        <a href="{{ route('installer.requirement') }}" class="p-3 px-6 rounded-lg inline-flex items-center justify-center gap-3 bg-primary text-white">
            {{ trans('installer.welcome.next') }}
            <i class="fa-solid fa-angle-right text-sm"></i>
        </a>
    </div>
@endsection
