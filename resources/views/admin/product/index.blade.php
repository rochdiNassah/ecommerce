@extends('layouts.main')
@section('title', 'Products')

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full p-2">
            <form>
                <div class="self-center mb-4">
                    <input class="w-40 sm:w-64 md:w-400 h-10 text-gray-200 bg-gray-900 border border-gray rounded-full appearance-none p-3 text-sm leading-tight outline-none" type="text" value="{{ request('search') ?? null }}" name="search" placeholder="Search by name">
                </div>
                <div class="self-center flex space-x-2 space-y-2">
                    <select class="self-center bg-lightdark border border-gray w-full p-3 font-bold text-xs text-gray-300 rounded-md" name="sort">
                        <option value="" @if ('all' === request('sort')) selected @endif>Sort by price</option>
                        @foreach (['lowest', 'highest'] as $sort)
                            <option value="{{ $sort }}" @if ($sort === request('sort')) selected @endif>{{ ucfirst($sort) }}</option>
                        @endforeach
                    </select>
    
                    <button class="self-center transition bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-{{ $mainColor }}-300 p-2 px-4 text-md font-bold rounded-md">Sort</button>
                </div>
            </form>
            <div class="p-2 w-full sm:w-4/5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($products as $product)
                <div class="bg-dark border border-gray rounded-sm relative space-y-2">
                    <div class="p-2">
                        <div class="space-y-2 mt-2">
                            <img class="object-contain h-20" src="{{ asset($product->image_path) }}" onerror="this.src='{{ config('app.default_product_image_path') }}'" alt="Image"/>
                            <div class="space-y-2">
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="text-gray-400 text-xs">Product ID:</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">#{{ $product->id }}</p>
                                </div>
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="text-gray-400 text-xs">Product name:</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $product->name }}</p>
                                </div>
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="text-gray-400 text-xs">Orders count:</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">500</p>
                                </div>
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="text-gray-400 text-xs">Product price:</p>
                                    <p class="text-xs font-bold text-{{ $mainColor }}-300 truncate">{{ $product->price }}$</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray p-2">
                        <a
                            class="block w-full text-center font-bold bg-red-800 hover:bg-red-900 transition text-red-300 transition text-xs py-1 px-2 rounded-sm"
                            href="{{ route('product.delete', $product->id) }}"
                        >Delete</a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-4 mb-8 mt-4 grid place-items-center">
                {{ $products->appends(['sort' => $sort, 'search' => $search])->links() }}
            </div>
        </div>
    </div>
@stop