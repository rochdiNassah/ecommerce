@extends('layouts.main')
@section('title', 'Log In')

@php
$inputs = [
    ['type' => 'email', 'name' => 'email', 'label' => 'Email Address'],
    ['type' => 'password', 'name' => 'password', 'label' => 'Password']
];
@endphp

@section('content')
    @include('layouts.navbar')

<div class="px-2 sm:px-0">
    <div class="border bg-white dark:bg-gray-800 border-gray mx-auto w-full sm:w-400 px-8 py-4 rounded-sm my-20">
        <form method="post">
            @csrf

            <h1 class="my-2 font-bold text-xl text-current-600 dark:text-current-400">Log In into {{ config('app.name') }}</h1>

            @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
            @endif

            <div class="my-3">
                @foreach ($inputs as $input)
                <label class="text-xs text-gray-500 dark:text-gray-300">{{ $input['label'] }}</label>
                <input
                    class="text-gray-600 dark:text-gray-600 dark:text-gray-200 dark:bg-gray-800 border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                    type="{{ $input['type'] }}"
                    name="{{ $input['name'] }}"
                    value="{{ old($input['name']) }}"
                    autocomplete="off"
                >
                @endforeach

                <div class="my-2 mb-4 flex items-center justify-between" style="margin-top: -4px">
                    <div>
                        <label class="block text-gray-400" for="remember">
                            @php
                                $checked = 'on' === old('remember')
                                    ? true
                                    : (old('email') && null === old('remember')
                                        ? false
                                        : true)
                            @endphp
                            <input class="leading-tight cursor-pointer" type="checkbox" id="remember" name="remember" {{ $checked ? 'checked' : null }}>

                            <span class="text-gray-600 dark:text-gray-300 font-bold text-xs select-none cursor-pointer">Remember me</span>
                        </label>
                    </div>

                    <div>
                        <a href="{{ route('password.request') }}" class="transition text-xs text-current-600 hover:text-current-500 dark:text-current-400 dakr:hover:text-current-500 font-bold">Forgot password?</a>
                    </div>
                </div>

                <button class="rounded-sm my-2 transition w-full bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-sm font-bold py-2 px-4" type="submit">
                    Continue
                </button>
            </div>
        </form>

        <div class="mt-4 mb-2 flex justify-center">
            <p class="font-bold text-xs text-gray-600 dark:text-gray-300">Don't have an account?</p>&nbsp;
            <a href="{{ route('join') }}" class="transition font-bold text-xs text-current-600 hover:text-current-500 dark:text-current-400 dark:hover:text-current-500">Join</a>
        </div>
    </div>
</div>
@stop