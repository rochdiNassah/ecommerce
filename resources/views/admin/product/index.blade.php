@extends('layouts.main')
@section('title', 'Products')

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="p-2 w-full sm:w-4/5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($products as $product)
                <div class="bg-gray border border-gray rounded-sm p-4 relative">
                    <div class="flex">
                        <div class="text-center grid place-items-center space-y-1">
                            <img class="h-16 w-16" src="{{ asset($product->image_path) }}" alt="Image"/>
                            <div class="text-sm rounded-sm text-gray-200 w-20">{{ $product->name }}</div>
                        </div>
    
                        <div class="absolute right-4 rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-200 bg-{{ $mainColor }}-900 font-bold text-xs truncate">{{ $product->price }}$</div>
                    </div>

                    <a
                        class="mt-1 block w-full text-center font-bold bg-red-900 hover:bg-red-800 transition text-red-400 text-xs py-1 px-2 rounded-sm"
                        href="{{ route('product.delete', $product->id) }}"
                    >Delete</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop