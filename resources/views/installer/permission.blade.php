@extends('installer.layouts.master')

@section('template_title')
    {{ trans('installer.permission.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer.permission.title') }}
@endsection

@section('container')
    <ul class="installer-track">
        <li onclick="handleLinkForInstaller('{{ route('installer.index') }}')" class="done">
            <i class="fa-solid fa-house"></i>
        </li>
        <li onclick="handleLinkForInstaller('{{ route('installer.requirement') }}')" class="done">
            <i class="fa-solid fa-server"></i>
        </li>
        <li class="active"><i class="fa-sharp fa-solid fa-unlock"></i></li>
        <li><i class="fa-solid fa-key"></i></li>
        <li><i class="fa-solid fa-gear"></i></li>
        <li><i class="fa-solid fa-database"></i></li>
        <li><i class="fa-solid fa-unlock-keyhole"></i></li>
    </ul>

    <span class="my-6 w-full h-[1px] bg-[#EFF0F6]"></span>

    <ul class="w-full rounded-lg overflow-hidden mb-8 border border-[#D9DBE9]">
        <li class="flex items-center justify-between py-3.5 px-6 border-b border-[#EFF0F6] last:border-none bg-[#F7F7FC]">
            <h3 class="text-sm font-semibold capitalize">{{ trans('installer.permission.permission_checking') }}</h3>
        </li>
        @foreach($permissions['permissions'] as $permission)
            <li class="flex items-center justify-between py-3.5 px-6 border-b border-[#EFF0F6] last:border-none">
                <span class="text-sm font-medium text-heading">{{ $permission['folder'] }} - {{ $permission['permission'] }}</span>
                <i class="fa-solid fa-{{ $permission['isSet'] ? 'check-circle' : 'exclamation-circle' }} circle-check text-sm text-[#{{ $permission['isSet'] ? '1AB759' : 'E93C3C' }}]"></i>
            </li>
        @endforeach
    </ul>

    @if ( ! isset($permissions['errors']))
        <a href="{{ route('installer.license') }}"
           class="w-fit mx-auto p-3 px-6 rounded-lg flex items-center justify-center gap-3 bg-primary text-white">
            <span class="text-sm font-medium capitalize">{{ trans('installer.permission.next') }}</span>
            <i class="fa-solid fa-angle-right text-sm"></i>
        </a>
    @endif
@endsection
