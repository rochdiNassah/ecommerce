@extends('layouts.main')
@section('title', 'Products')

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full p-4">
            <div class="w-full sm:w-4/5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($products as $product)
                <div class="bg-dark border border-gray rounded-sm p-4 relative">
                    <div class="flex justify-center">
                        <div class="text-center grid place-items-center space-y-1">
                            <img class="object-contain h-40" src="{{ asset($product->image_path) }}" onerror="this.src='{{ config('app.default_product_image_path') }}'" alt="Image"/>
                            <div class="text-sm rounded-sm text-gray-200 font-bold w-20 truncate">{{ $product->name }}</div>
                        </div>
    
                        <div class="absolute right-4 rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-800 bg-{{ $mainColor }}-200 font-bold text-xs truncate">{{ $product->price }}$</div>
                    </div>

                    <a
                        class="mt-1 block w-full text-center font-bold bg-red-800 hover:bg-red-900 transition text-red-300 transition text-xs py-1 px-2 rounded-sm"
                        href="{{ route('product.delete', $product->id) }}"
                    >Delete</a>
                </div>
                @endforeach
            </div>
            <div class="px-4 mb-8 mt-4 grid place-items-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@stop