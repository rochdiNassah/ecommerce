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

<div class="px-2 sm:px-0">
    <div class="border bg-dark border-gray mx-auto xs:w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 base-form rounded-sm px-4 sm:px-10 py-4 my-20">
        <form method="post">
            @csrf

            <h1 class="my-2 font-bold text-xl text-{{ $mainColor }}-400">Log In into {{ config('app.name') }}</h1>

            @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
            @endif

            <div class="my-3">
                @foreach ($inputs as $input)
                <label class="font-bold text-sm text-gray-300">{{ $input['label'] }}</label>
                <input
                    class="text-gray-200 bg-lightdark border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
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

                            <span class="text-gray-300 font-bold text-sm select-none cursor-pointer">Remember me</span>
                        </label>
                    </div>

                    <div>
                        <a href="{{ route('password.request') }}" class="transition text-sm text-{{ $mainColor }}-400 hover:text-{{ $mainColor }}-500 font-bold">Forgot password?</a>
                    </div>
                </div>

                <button class="rounded-sm my-2 transition w-full text-{{ $mainColor }}-300 bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-sm font-bold py-2 px-4" type="submit">
                    Continue
                </button>
            </div>
        </form>

        <div class="mt-4 mb-2 flex justify-center">
            <p class="font-bold text-sm text-gray-300">Don't have an account?</p>&nbsp;
            <a href="{{ route('join') }}" class="transition font-bold text-sm text-{{ $mainColor }}-400 hover:text-{{ $mainColor }}-500">Join</a>
        </div>
    </div>
</div>
@stop