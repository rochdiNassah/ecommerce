@extends('layouts.main')
@section('title', 'Home')

@section('content')
    @include('layouts.navbar')
    
    <div class="container mx-auto">
        <div class="p-4 grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 place-items-center">
            @foreach ($products as $product)
            <div class="w-80 bg-gray border border-gray rounded-sm relative">
                <div class="flex justify-between border-b border-gray p-2">
                    <div class="text-sm rounded-sm text-gray-700 font-bold truncate">{{ $product->name }}</div>
                    <div class="rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-700 bg-{{ $mainColor }}-200 font-bold text-xs truncate">{{ $product->price }}$</div>
                </div>

                <img class="p-2 w-full h-60" src="{{ asset($product->image_path) }}" alt="Image"/>

                <div class="border-t border-gray w-full p-2">
                    <a
                        class="block w-full text-center font-bold bg-{{ $mainColor }}-200 hover:bg-{{ $mainColor }}-300 transition text-{{ $mainColor }}-700 text-sm py-1 px-2 rounded-sm"
                        href="{{ route('order.create-view', $product->id) }}"
                    >Order</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop