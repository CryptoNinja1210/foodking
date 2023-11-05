@extends('installer.layouts.master')

@section('template_title')
    {{ trans('installer.license.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer.license.title') }}
@endsection

@section('container')
    <ul class="installer-track">
        <li onclick="handleLinkForInstaller('{{ route('installer.index') }}')" class="done">
            <i class="fa-solid fa-house"></i>
        </li>
        <li onclick="handleLinkForInstaller('{{ route('installer.requirement') }}')" class="done">
            <i class="fa-solid fa-server"></i>
        </li>
        <li onclick="handleLinkForInstaller('{{ route('installer.permission') }}')" class="done">
            <i class="fa-sharp fa-solid fa-unlock"></i>
        </li>
        <li class="active"><i class="fa-solid fa-key"></i></li>
        <li><i class="fa-solid fa-gear"></i></li>
        <li><i class="fa-solid fa-database"></i></li>
        <li><i class="fa-solid fa-unlock-keyhole"></i></li>
    </ul>

    <span class="my-6 w-full h-[1px] bg-[#EFF0F6]"></span>

    <form method="post" action="{{ route('installer.licenseStore') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="mb-4">
            <label class="text-sm font-medium block mb-1.5 text-heading">
                {{ trans('installer.license.label.license_key') }} <span class="text-[#E93C3C]">*</span>
                <span class="text-primary modal-show underline cursor-pointer">({{ trans('installer.license.active_process') }})</span>
            </label>
            <input name="license_key" type="text" value="{{ old('license_key') }}"
                   class="w-full h-12 rounded-lg px-4 border border-[#D9DBE9]">
            @if ($errors->has('license_key'))
                <small class="block mt-2 text-sm font-medium text-[#E93C3C]">{{ $errors->first('license_key') }}</small>
            @endif
            @if($errors->has('global'))
                <small class="block mt-2 text-sm font-medium text-[#E93C3C]">{{ $errors->first('global') }}</small>
            @endif
        </div>

        <button type="submit"
                class="w-fit mx-auto p-3 px-6 rounded-lg flex items-center justify-center gap-3 bg-primary text-white">
            <span class="text-sm font-medium capitalize">{{ trans('installer.license.next') }}</span>
            <i class="fa-solid fa-angle-right text-sm"></i>
        </button>
    </form>

    <div id="installer-modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ trans('installer.license.active_process') }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"></button>
            </div>
            <div class="modal-body">
                <section class="mb-5">
                    <h4 class="mb-2 font-bold">{{ __('Step1: ') }} <a href="{{ config('product.officialSite') }}" target="_blank">{{ __(' Go to iNilabs') }}</a></h4>
                    <picture>
                        <img src="{{asset('images/installer/home.png')}}" class="img-fluid img-thumbnail image-css"  alt="...">
                    </picture>
                </section>
                <section class="mb-5">
                    <h4 class="mb-2 font-bold">{{ __('Step2: ') }} <a href="{{ config('product.loginUrl') }}" target="_blank">{{ __(' Login to iNilabs') }}</a></h4>
                    <picture>
                        <img src="{{asset('images/installer/login.png')}}" class="img-fluid img-thumbnail image-css"  alt="...">
                    </picture>
                </section>
                <section class="mb-5">
                    <h4 class="mb-2 font-bold">{{ __('Step3: ') }} <a href="{{ config('product.activeLicense') }}" target="_blank">{{ __(' Active your license code') }} </a></h4>
                    <h6>{{ __('You can easily get the activation code and try to install your product by this code.') }}</h6>
                    <picture class="mt-1">
                        <img src="{{asset('images/installer/active.png')}}" class="img-fluid img-thumbnail image-css"  alt="...">
                    </picture>
                </section>
            </div>
        </div>
    </div>
@endsection
