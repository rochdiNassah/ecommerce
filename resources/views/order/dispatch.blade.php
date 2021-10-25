@extends('layouts.main')
@section('title', "Dispatch #{$order->id}")

@section('content')
    @include('layouts.navbar')

    <div class="px-2 container mx-auto max-w-7xl flex flex-wrap justify-center my-20">
        <div class="px-4 sm:px-10 py-4 bg-dark base-form border border-gray rounded-sm">
            <form action="{{ route('order.dispatch') }}" method="post">
                <h1 class="my-2 font-bold text-xl text-{{ $mainColor }}-400">Dispatch order #{{ $order->id }}</h1>

                @csrf
                
                @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
                @endif

                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <label class="font-bold text-sm text-gray-300">Choose a delivery driver</label>
                <select class="bg-lightdark border border-gray w-full p-3 mb-6 font-bold text-sm text-gray-300 rounded-sm appearance-none focus:shadow-outline" name="delivery_driver">
                    @foreach ($delivery_drivers as $delivery_driver)
                        <option value="{{ $delivery_driver->id }}">{{ $delivery_driver->fullname }}</option>
                    @endforeach
                </select>

                <button class="rounded-sm mb-2 transition w-full text-{{ $mainColor }}-300 bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-sm py-2 px-4 font-bold" type="submit">
                    Dispatch
                </button>
            </form>
        </div>
    </div>
@stop