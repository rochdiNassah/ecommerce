@extends('layouts.main')
@section('title', "Request to track order")

@section('content')
    @include('layouts.navbar')

    <div class="px-2 container mx-auto max-w-7xl flex flex-wrap justify-center my-10">
        <div class="border bg-white dark:bg-gray-800 border-gray mx-auto w-full sm:w-400 px-8 py-4 rounded-sm my-20">
            <form action="{{ route('order.request-my-orders') }}" method="post">
                <h1 class="my-2 font-bold text-sm text-current-600 dark:text-current-400">We will send a link to you where you can view all of your orders from.</h1>

                @csrf
                
                @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
                @endif

                <label class="text-xs text-gray-600 dark:text-gray-300">Please enter the email your orders placed with.</label>
                <input
                    class="text-gray-600 dark:text-gray-200 dark:bg-gray-600 border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="off"
                    placeholder="Email address"
                >

                <button class="rounded-sm mb-2 transition w-full bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-sm py-2 px-4 font-bold" type="submit">
                    Send
                </button>
            </form>
        </div>
    </div>
@stop