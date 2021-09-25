@extends('layouts.main')
@section('title', 'Join')

@php
$inputs = [
    ['type' => 'text', 'name' => 'fullname', 'label' => 'Full Name'],
    ['type' => 'text', 'name' => 'email', 'label' => 'Email Address'],
    ['type' => 'text', 'name' => 'phone_number', 'label' => 'Phone Number'],
    ['type' => 'password', 'name' => 'password', 'label' => 'Password'],
    ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'Confirm Password'],
];
@endphp

@section('content')
<div class="mx-auto xs:w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 auth-form rounded-sm px-10 py-4 my-20">
    <form method="post">
        @csrf

        <h1 class="my-2 font-bold text-xl text-blue-500">Join to {{ config('app.name') }}</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $error }}</strong>
                </div>
            @endforeach
        @endif

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

            <label class="font-bold text-sm text-gray-400">The role you want to apply for</label>
            <select class="w-full p-3 mb-6 font-bold text-sm placeholder-gray-600 text-gray-200 rounded-sm appearance-none focus:shadow-outline" name="role">
                <option>Please choose a role from here</option>
                <option {{ old('role') === 'delivery_driver' ? 'selected' : null }} value="delivery_driver">Delivery Driver</option>
                <option {{ old('role') === 'dispatcher' ? 'selected' : null }} value="dispatcher">Dispatcher</option>
                <option {{ old('role') === 'admin' ? 'selected' : null }} value="admin">Admin</option>
            </select>

            <button class="rounded-sm my-2 transition w-full text-gray-400 bg-gray-700 hover:bg-gray-600 font-bold py-2 px-4" type="submit">
                Send Join Request
            </button>
        </div>
    </form>

    <div class="mt-4 mb-2 flex justify-center">
        <p class="font-bold text-sm text-gray-200">Already a member?</p>&nbsp;
        <a href="/login" class="transition font-bold text-sm text-blue-500 hover:text-blue-600">Log In</a>
    </div>
</div>
@stop