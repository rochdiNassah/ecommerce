@extends('layouts.main')
@section('title', 'Home')

@section('content')
    @include('layouts.navbar')
    <h1 class="text-center font-bold text-gray-600 dark:text-gray-200 text-xl sm:text-2xl my-8">Deliver to your doorstep!</h1>

    <div class="container mx-auto lg:max-w-5xl">
        <div class="px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 place-items-center gap-4">
            @foreach ($products as $product)
            <div class="w-80 sm:w-60 bg-white dark:bg-gray-800 border border-gray rounded-sm relative">
                <div class="flex justify-between border-b border-gray p-2">
                    <div class="self-center text-sm rounded-sm text-gray-600 dark:text-gray-200 font-bold truncate">{{ $product->name }}</div>
                    <div class="rounded-xl px-2 py-1 text-center text-current-600 bg-current-100 font-bold text-xs truncate">{{ $product->price }}$</div>
                </div>

                <img class="object-contain p-2 w-full h-60 sm:h-40" src="{{ asset($product->image_path) }}" alt="Image"/>

                <div class="border-t border-gray w-full p-2">
                    <a
                        class="block w-full text-center font-bold bg-current-100 hover:bg-current-200 text-current-600 dark:bg-current-800 dark:hover:bg-current-900 dark:text-current-300 transition  text-sm py-1 px-2 rounded-sm"
                        href="{{ route('order.create-view', $product->id) }}"
                    >Order</a>
                </div>
            </div>
            @endforeach
        </div>
        @if ($is_paginating)
            <div class="px-4 my-8">
                {{ $products->appends(['search' => $search])->links() }}
            </div>
        @endif
    </div>
    @include('layouts.footer')
@stop