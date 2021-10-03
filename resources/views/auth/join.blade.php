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
    @include('layouts.navbar')

<div class="border border-gray mx-auto xs:w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 auth-form rounded-sm px-10 py-4 my-20">
    <form method="post" enctype="multipart/form-data">
        @csrf

        <h1 class="my-2 font-bold text-xl text-{{ $mainColor }}-400">Join to {{ config('app.name') }}</h1>

        @if ($errors->any())
            <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                <strong>{{ $errors->all()[0] }}</strong>
            </div>
        @endif

        <div class="my-3">
            <div class="grid place-items-center my-4">
                <img class="self-center w-20 h-20 bg-gray-200 rounded-full" src="{{ asset('images/avatars/default.jpg') }}" id="avatarImg">

                <label class="cursor-pointer mt-6">
                    <span class="mt-2 leading-normal px-4 py-2 bg-{{ $mainColor }}-900 hover:bg-{{ $mainColor }}-800 transition text-{{ $mainColor }}-400 font-bold text-sm rounded-sm">Select Image</span>
                    <input class="hidden" type="file" name="avatar" id="avatarInput">
                </label>
            </div>

            @foreach ($inputs as $input)
            <label class="font-bold text-sm text-gray-300">{{ $input['label'] }}</label>
            <input
                class="bg-gray-light text-white border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                type="{{ $input['type'] }}"
                name="{{ $input['name'] }}"
                value="{{ old($input['name']) }}"
                autocomplete="off"
            >
            @endforeach

            <label class="font-bold text-sm text-gray-300">The role you want to apply for</label>
            <select class="bg-gray-light border border-gray w-full p-3 mb-6 font-bold text-sm text-gray-200 rounded-sm appearance-none focus:shadow-outline" name="role">
                <option>Please choose a role from here</option>
                <option {{ old('role') === 'delivery_driver' ? 'selected' : null }} value="delivery_driver">Delivery Driver</option>
                <option {{ old('role') === 'dispatcher' ? 'selected' : null }} value="dispatcher">Dispatcher</option>
                <option {{ old('role') === 'admin' ? 'selected' : null }} value="admin">Admin</option>
            </select>

            <button class="rounded-sm my-2 transition w-full text-{{ $mainColor }}-400 bg-{{ $mainColor }}-900 hover:bg-{{ $mainColor }}-800 text-sm py-2 px-4" type="submit">
                Send Join Request
            </button>
        </div>
    </form>

    <div class="mt-4 mb-2 flex justify-center">
        <p class="font-bold text-sm text-gray-200">Already a member?</p>&nbsp;
        <a href="{{ route('login') }}" class="transition font-bold text-sm text-{{ $mainColor }}-400 hover:text-{{ $mainColor }}-500">Log In</a>
    </div>
</div>

<script>
    var avatarInput = document.getElementById('avatarInput')
    var avatarImg = document.getElementById('avatarImg')

    avatarInput.addEventListener('change', function (e) {
        avatarImg.src = URL.createObjectURL(e.target.files[0])
    })
</script>
@stop