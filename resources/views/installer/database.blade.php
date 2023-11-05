@extends('installer.layouts.master')

@section('template_title')
    {{ trans('installer.database.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer.database.title') }}
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
        <li class="active"><i class="fa-solid fa-database"></i></li>
        <li><i class="fa-solid fa-unlock-keyhole"></i></li>
    </ul>

    <span class="my-6 w-full h-[1px] bg-[#EFF0F6]"></span>


    @if($errors->has('global'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-5 rounded relative" role="alert">
            <span class="block sm:inline text-[#E93C3C]">{{ $errors->first('global') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer close-alert-button">
                <i class="fa fa-close margin-top-5-px"></i>
            </span>
        </div>
    @endif
    <form method="post" action="{{ route('installer.databaseStore') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="mb-4">
            <label class="text-sm font-medium block mb-1.5 text-heading">
                {{ trans('installer.database.label.database_host') }} <span class="text-[#E93C3C]">*</span>
            </label>
            <input name="database_host" value="{{ old('database_host', 'localhost') }}" type="text"
                   class="w-full h-12 rounded-lg px-4 border border-[#D9DBE9]">
            @if ($errors->has('database_host'))
                <small
                    class="block mt-2 text-sm font-medium text-[#E93C3C]">{{ $errors->first('database_host') }}</small>
            @endif
        </div>

        <div class="mb-4">
            <label class="text-sm font-medium block mb-1.5 text-heading">
                {{ trans('installer.database.label.database_port') }} <span class="text-[#E93C3C]">*</span>
            </label>
            <input name="database_port" value="{{ old('database_port', '3306') }}" type="text"
                   class="w-full h-12 rounded-lg px-4 border border-[#D9DBE9]">
            @if ($errors->has('database_port'))
                <small
                    class="block mt-2 text-sm font-medium text-[#E93C3C]">{{ $errors->first('database_port') }}</small>
            @endif
        </div>

        <div class="mb-4">
            <label class="text-sm font-medium block mb-1.5 text-heading">
                {{ trans('installer.database.label.database_name') }} <span class="text-[#E93C3C]">*</span>
            </label>
            <input name="database_name" value="{{ old('database_name') }}" type="text"
                   class="w-full h-12 rounded-lg px-4 border border-[#D9DBE9]">
            @if ($errors->has('database_name'))
                <small
                    class="block mt-2 text-sm font-medium text-[#E93C3C]">{{ $errors->first('database_name') }}</small>
            @endif
        </div>

        <div class="mb-4">
            <label class="text-sm font-medium block mb-1.5 text-heading">
                {{ trans('installer.database.label.database_username') }} <span class="text-[#E93C3C]">*</span>
            </label>
            <input name="database_username" value="{{ old('database_username') }}" type="text"
                   class="w-full h-12 rounded-lg px-4 border border-[#D9DBE9]">
            @if ($errors->has('database_username'))
                <small
                    class="block mt-2 text-sm font-medium text-[#E93C3C]">{{ $errors->first('database_username') }}</small>
            @endif
        </div>

        <div class="mb-8">
            <label class="text-sm font-medium block mb-1.5 text-heading">
                {{ trans('installer.database.label.database_password') }} <span class="text-[#E93C3C]">*</span>
            </label>
            <input name="database_password" value="{{ old('database_password') }}" type="text"
                   class="w-full h-12 rounded-lg px-4 border border-[#D9DBE9]">
            @if ($errors->has('database_password'))
                <small
                    class="block mt-2 text-sm font-medium text-[#E93C3C]">{{ $errors->first('database_password') }}</small>
            @endif
        </div>

        <button type="submit"
                class="w-fit mx-auto p-3 px-6 rounded-lg flex items-center justify-center gap-3 bg-primary text-white">
            <span class="text-sm font-medium capitalize">{{ trans('installer.database.next') }}</span>
            <i class="fa-solid fa-angle-right text-sm"></i>
        </button>
    </form>

@endsection
