@extends('installer.layouts.master')

@section('template_title')
    {{ trans('installer.requirement.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer.requirement.title') }}
@endsection

@section('container')
    <ul class="installer-track">
        <li onclick="handleLinkForInstaller('{{ route('installer.index') }}')" class="done">
            <i class="fa-solid fa-house"></i>
        </li>
        <li class="active"><i class="fa-solid fa-server"></i></li>
        <li><i class="fa-sharp fa-solid fa-unlock"></i></li>
        <li><i class="fa-solid fa-key"></i></li>
        <li><i class="fa-solid fa-gear"></i></li>
        <li><i class="fa-solid fa-database"></i></li>
        <li><i class="fa-solid fa-unlock-keyhole"></i></li>
    </ul>

    <span class="my-6 w-full h-[1px] bg-[#EFF0F6]"></span>

    @foreach($requirements['requirements'] as $type => $requirement)
        <ul class="w-full rounded-lg overflow-hidden mb-8 border border-[#D9DBE9]">
            <li class="flex items-center justify-between gap-2 py-3.5 px-6 border-b border-[#EFF0F6] last:border-none bg-[#F7F7FC]">
                @if($type == 'php')
                    <h3 class="text-sm font-semibold capitalize">{{ ucfirst($type) }}
                        <span
                            class="text-xs font-medium lowercase">( {{ trans('installer.requirement.version') }} {{ $phpSupportInfo['minimum'] }} {{ trans('installer.requirement.required') }})</span>
                    </h3>
                    <span class="flex items-center gap-1 text-[#1AB759]">
                    <span class="text-sm font-semibold">{{ $phpSupportInfo['current'] }}</span>
                    <i class="fa-solid fa-{{ $phpSupportInfo['supported'] ? 'check-circle' : 'exclamation-circle' }} text-sm text-[#{{ $phpSupportInfo['supported'] ? '1AB759' : 'E93C3C' }}]"></i>
                </span>
                @endif
            </li>

            @foreach($requirements['requirements'][$type] as $extension => $enabled)
                <li class="flex items-center justify-between py-3.5 px-6 border-b border-[#EFF0F6] last:border-none">
                    <span class="text-sm font-medium capitalize text-heading">{{ $extension }}</span>
                    <i class="fa-solid fa-{{ $enabled ? 'circle-check' : 'exclamation-circle' }} text-sm text-[#{{ $enabled ? '1AB759' : 'E93C3C' }}]"></i>
                </li>
            @endforeach
        </ul>
    @endforeach

    @if ( ! isset($requirements['errors']) && $phpSupportInfo['supported'] )
        <a href="{{ route('installer.permission') }}"
           class="w-fit mx-auto p-3 px-6 rounded-lg flex items-center justify-center gap-3 bg-primary text-white">
            <span class="text-sm font-medium capitalize">{{ trans('installer.requirement.next') }}</span>
            <i class="fa-solid fa-angle-right text-sm"></i>
        </a>
    @endif
@endsection
