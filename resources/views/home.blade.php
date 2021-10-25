@extends('layouts.main')
@section('title', 'Home')

@section('content')
    @include('layouts.navbar')
    <h1 class="text-center font-bold text-gray-200 text-xl sm:text-2xl mt-8 mb-6">Deliver to your doorstep!</h1>

    <div class="container mx-auto lg:max-w-7xl">
        <div class="mb-12 p-4 grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 3xl:grid-cols-4 place-items-center">
            @foreach ($products as $product)
            <div class="w-80 bg-lightdark border border-gray rounded-sm relative">
                <div class="flex justify-between border-b border-gray p-2">
                    <div class="self-center text-sm rounded-sm text-gray-200 font-bold truncate">{{ $product->name }}</div>
                    <div class="rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-900 bg-{{ $mainColor }}-100 font-bold text-xs truncate">{{ $product->price }}$</div>
                </div>

                <img class="object-contain p-2 w-full h-60" src="{{ asset($product->image_path) }}" alt="Image"/>

                <div class="border-t border-gray w-full p-2">
                    <a
                        class="block w-full text-center font-bold bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 transition text-{{ $mainColor }}-300 text-sm py-1 px-2 rounded-sm"
                        href="{{ route('order.create-view', $product->id) }}"
                    >Order</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @include('layouts.footer')
@stop