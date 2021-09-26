@extends('layouts.main')
@section('title', 'Log In')

@php
$inputs = [
    ['type' => 'text', 'name' => 'email', 'label' => 'Email Address'],
    ['type' => 'password', 'name' => 'password', 'label' => 'Password']
];
@endphp

@section('content')
    @include('layouts.navbar')

<div class="mx-auto xs:w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 auth-form rounded-sm px-10 py-4 my-20">
    <form method="post">
        @csrf

        <h1 class="my-2 font-bold text-xl text-green-500">Log In into {{ config('app.name') }}</h1>

        {{-- @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                <strong>{{ $error }}</strong>
            </div>
            @endforeach
        @endif --}}

        <div class="my-3">
            @foreach ($inputs as $input)
            <label class="font-bold text-sm text-gray-400">{{ $input['label'] }}</label>
            <input
                class="font-bold rounded-sm mb-6 appearance-none w-full p-3 text-white leading-tight outline-none"
                type="{{ $input['type'] }}"
                name="{{ $input['name'] }}"
                value="{{ old($input['name']) }}"
                autocomplete="off"
            >
            @endforeach

            <div class="my-2 mb-4 flex items-center justify-between" style="margin-top: -4px">
                <div>
                    <label class="block text-gray-500" for="remember">
                        <input class="leading-tight cursor-pointer" type="checkbox" id="remember" name="remember">

                        <span class="font-bold text-gray-200 text-sm select-none cursor-pointer">Remember me</span>
                    </label>
                </div>

                <div>
                    <a href="#" class="transition font-bold text-sm text-green-500 hover:text-green-600">Forgot password?</a>
                </div>
            </div>

            <button class="rounded-sm my-2 transition w-full text-green-300 bg-green-700 hover:bg-green-600 font-bold py-2 px-4" type="submit">
                Continue
            </button>
        </div>
    </form>

    <div class="mt-4 mb-2 flex justify-center">
        <p class="font-bold text-sm text-gray-200">Don't have an account?</p>&nbsp;
        <a href="/join" class="transition font-bold text-sm text-green-500 hover:text-green-600">Join</a>
    </div>
</div>
@stop