@extends('installer.layouts.master')

@section('template_title')
    {{ trans('installer.final.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer.final.title') }}
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
        <li onclick="handleLinkForInstaller('{{ route('installer.license') }}')" class="done">
            <i class="fa-solid fa-key"></i>
        </li>
        <li onclick="handleLinkForInstaller('{{ route('installer.site') }}')" class="done">
            <i class="fa-solid fa-gear"></i>
        </li>
        <li onclick="handleLinkForInstaller('{{ route('installer.database') }}')" class="done">
            <i class="fa-solid fa-database"></i>
        </li>
        <li class="active"><i class="fa-solid fa-unlock-keyhole"></i></li>
    </ul>

    <span class="my-6 w-full h-[1px] bg-[#EFF0F6]"></span>

    <h3 class="text-lg font-medium text-center mb-8 text-[#1AB759]">{{ trans('installer.final.success_message') }}</h3>

    @if($errors->has('global'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-5 rounded relative" role="alert">
            <span class="block sm:inline text-[#E93C3C]">{{ $errors->first('global') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer close-alert-button">
                <i class="fa fa-close margin-top-5-px"></i>
            </span>
        </div>
    @endif
    <dl class="w-full max-w-sm mx-auto mb-8 rounded-lg bg-[#F7F7FC]">
        <dt class="text-sm font-medium py-3 px-4 border-b border-[#D9DBE9] text-heading">{{ trans('installer.final.login_info') }}</dt>
        <dd class="py-1.5 px-4">
            <div class="text-sm text-heading py-1.5">
                <span class="w-20">{{ trans('installer.final.email') }}:</span>
                <span class="font-semibold">{{ trans('installer.final.email_info') }}</span>
            </div>
            <div class="text-sm text-heading py-1.5">
                <span class="w-20">{{ trans('installer.final.password') }}:</span>
                <span class="font-semibold">{{ trans('installer.final.password_info') }}</span>
            </div>
        </dd>
    </dl>

    <div class="text-center">
        <a href="{{ route('installer.finalStore') }}"
           class="p-3 px-6 rounded-lg inline-flex items-center justify-center gap-3 bg-primary text-white">
            {{ trans('installer.final.next') }}
            <i class="fa-solid fa-angle-right text-sm"></i>
        </a>
    </div>
@endsection
