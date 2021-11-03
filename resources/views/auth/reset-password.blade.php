@extends('layouts.main')
@section('title', 'Reset password')

@php
$inputs = [
    ['type' => 'email', 'name' => 'email', 'label' => 'Email Address', 'value' => request('email')],
    ['type' => 'password', 'name' => 'password', 'label' => 'Password'],
    ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'Confirm Password'],
];
@endphp

@section('content')
    @include('layouts.navbar')

<div class="px-2 sm:px-0">
    <div class="border bg-white border-gray mx-auto w-full sm:w-400 px-8 py-4 rounded-sm my-20">
        <form action="{{ route('password.update') }}" method="post">
            @csrf

            <h1 class="my-2 font-bold text-xl text-current-600 dark:text-current-400">Reset your password</h1>

            @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
            @endif

            <div class="my-3">
                <input type="hidden" name="token" value="{{ $token }}">
                @foreach ($inputs as $input)
                <label class="text-xs text-gray-500 dark:text-gray-300">{{ $input['label'] }}</label>
                <input
                    class="text-gary-600 dark:text-gray-200 dark:bg-lightdark border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                    type="{{ $input['type'] }}"
                    name="{{ $input['name'] }}"
                    value="{{ $input['value'] ?? null }}"
                    autocomplete="off"
                >
                @endforeach

                <button class="rounded-sm my-2 transition w-full bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-sm font-bold py-2 px-4" type="submit">
                    Continue
                </button>
            </div>
        </form>

        <div class="mt-4 mb-2 flex justify-center">
            <p class="font-bold text-xs text-gray-600 dark:text-gray-300">Remember your password?</p>&nbsp;
            <a href="{{ route('login') }}" class="transition font-bold text-xs text-current-600 hover:text-current-500 dark:text-current-400 dark:hover:text-current-500">Log In</a>
        </div>
    </div>
</div>
@stop