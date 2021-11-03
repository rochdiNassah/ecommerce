@extends('layouts.main')
@section('title', "Dispatch #{$order->id}")

@section('content')
<div class="flex">
    @include('dispatcher.sidebar')

    <div class="px-2 container mx-auto max-w-7xl flex flex-wrap justify-center my-20">
        <div class="border bg-white border-gray mx-auto w-full sm:w-400 px-8 py-4 rounded-sm">
            <form action="{{ route('order.dispatch') }}" method="post">
                <h1 class="my-2 font-bold text-xl text-current-600 dark:text-current-400">Dispatch order #{{ $order->id }}</h1>

                @csrf
                
                @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
                @endif

                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <label class="text-xs text-gray-500 dark:text-gray-300">Choose a delivery driver</label>
                <select class="dark:bg-gray-800 border border-gray w-full p-3 mb-6 font-bold text-sm text-gray-600 dark:text-gray-300 rounded-sm appearance-none focus:shadow-outline" name="delivery_driver_id">
                    @foreach ($delivery_drivers as $delivery_driver)
                        <option value="{{ $delivery_driver->id }}">{{ $delivery_driver->fullname }}</option>
                    @endforeach
                </select>

                <button class="rounded-sm mb-2 transition w-full bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-sm py-2 px-4 font-bold" type="submit">
                    Dispatch
                </button>
            </form>
        </div>
    </div>
</div>
@stop