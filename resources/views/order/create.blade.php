@extends('layouts.main')
@section('title', "Order {$product->name}")

@php
$inputs = [
    ['type' => 'text', 'name' => 'fullname', 'label' => 'Full Name', 'value' => 'Foobar'],
    ['type' => 'text', 'name' => 'email', 'label' => 'Email Address', 'value' => 'foo@bar.baz'],
    ['type' => 'text', 'name' => 'phone_number', 'label' => 'Phone Number', 'value' => '0000000000'],
    ['type' => 'text', 'name' => 'address', 'label' => 'Address', 'value' => 'Morocco, Casablanca.']
];
@endphp

@section('content')
    @include('layouts.navbar')

    <div class="px-2 container mx-auto max-w-7xl flex flex-wrap justify-center my-20">
        <div class="px-4 sm:px-10 py-4 base-form border border-gray rounded-sm">
            <h1 class="w-full text-center font-bold text-gray-500 text-md sm:text-xl mb-4">Please fill the given form with your details to place your order.</h1>

            <form action="{{ route('order.create') }}" method="post">
                @csrf
                
                @if ($errors->any())
                <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                    <strong>{{ $errors->all()[0] }}</strong>
                </div>
                @endif

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                @foreach ($inputs as $input)
                <label class="font-bold text-sm text-gray-500">{{ $input['label'] }}</label>
                <input
                    class="text-gray-700 border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                    type="{{ $input['type'] }}"
                    name="{{ $input['name'] }}"
                    value="{{ old($input['name']) ?? $input['value'] ?? null }}"
                    autocomplete="off"
                >
                @endforeach

                <button class="rounded-sm mb-2 transition w-full text-{{ $mainColor }}-600 bg-{{ $mainColor }}-100 hover:bg-{{ $mainColor }}-200 text-sm py-2 px-4 font-bold" type="submit">
                    Place The Order
                </button>
            </form>

            <div class="mt-2 w-60 sm:w-80 bg-gray border border-gray rounded-sm relative self-start">
                <div class="flex justify-between border-b border-gray p-2">
                    <div class="text-sm rounded-sm text-gray-700 font-bold truncate">{{ $product->name }}</div>
                    <div class="rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-900 bg-{{ $mainColor }}-100 font-bold text-xs truncate">{{ $product->price }}$</div>
                </div>
    
                <img class="object-cover p-2 w-full h-60" src="{{ asset($product->image_path) }}" alt="Image"/>
            </div>
        </div>
    </div>
@stop