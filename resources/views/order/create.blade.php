@extends('layouts.main')
@section('title', "Order {$product->name}")

@php
$inputs = [
    ['type' => 'text', 'name' => 'fullname', 'label' => 'Full Name'],
    ['type' => 'email', 'name' => 'email', 'label' => 'Email Address'],
    ['type' => 'tel', 'name' => 'phone_number', 'label' => 'Phone Number']
];
@endphp

@section('content')
    @include('layouts.navbar')

    <div class="px-2 container mx-auto max-w-7xl flex flex-wrap justify-center my-16">
        <div class="dark:bg-gray-800 w-full sm:w-500 px-8 py-4 border border-gray rounded-sm">
            <div class="place-items-center mb-4 dark:bg-gray-800 border border-gray rounded-sm relative self-start">
                <div class="flex justify-between border-b border-gray p-2">
                    <div class="self-center text-sm rounded-sm text-gray-600 dark:text-gray-200 font-bold truncate">{{ $product->name }}</div>
                    <div class="rounded-xl px-2 py-1 text-center text-current-600 bg-current-100 font-bold text-xs truncate">{{ $product->price }}$</div>
                </div>
                
                <div class="grid place-items-center">
                    <img class="object-contain p-2 max-h-300" src="{{ asset($product->image_path) }}" alt="Image"/>
                </div>
            </div>
            
            <form action="{{ route('order.create') }}" method="post">
                @csrf
                
                @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
                @endif

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                @foreach ($inputs as $input)
                <label class="text-xs text-gray-500 dark:text-gray-300">{{ $input['label'] }}</label>
                <input
                    class="mb-2 text-gray-600 dark:text-gray-600 dark:text-gray-200 dark:bg-gray-800 border border-gray rounded-lg appearance-none w-full p-2 leading-tight outline-none"
                    type="{{ $input['type'] }}"
                    name="{{ $input['name'] }}"
                    value="{{ old($input['name']) ?? $input['value'] ?? null }}"
                    autocomplete="off"
                >
                @endforeach
                <label class="text-xs text-gray-500 dark:text-gray-300">Delivery address</label>
                <textarea class="mb-2 text-gray-600 dark:text-gray-600 dark:text-gray-200 dark:bg-gray-800 border border-gray rounded-lg appearance-none w-full p-2 leading-tight outline-none" name="address" cols="30" rows="2"></textarea>

                <button class="rounded-sm mt-2 transition w-full bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-sm py-2 px-4 font-bold" type="submit">
                    Place The Order
                </button>
            </form>
        </div>
    </div>
@stop