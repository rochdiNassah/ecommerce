@extends('layouts.main')
@section('title', "Request to track order")

@section('content')
    @include('layouts.navbar')

    <div class="px-2 container mx-auto max-w-7xl flex flex-wrap justify-center my-20">
        <div class="px-4 sm:px-10 py-4 bg-dark base-form border border-gray rounded-sm">
            <form action="{{ route('order.request-my-orders') }}" method="post">
                <h1 class="my-2 font-bold text-md text-{{ $mainColor }}-400">We will send a link to you where you can view all of your orders from.</h1>

                @csrf
                
                @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
                @endif

                <label class="text-xs text-gray-300">Please enter the email your orders placed with.</label>
                <input
                    class="text-gray-200 bg-lightdark border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                    type="email"
                    name="email"
                    value="{{ old('email') ?? 'rochdinassah.1998@gmail.com' }}"
                    autocomplete="off"
                >

                <button class="rounded-sm mb-2 transition w-full text-{{ $mainColor }}-300 bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-sm py-2 px-4 font-bold" type="submit">
                    Send
                </button>
            </form>
        </div>
    </div>
@stop