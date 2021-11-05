@extends('layouts.main')
@section('title', 'Products')

@section('content')
<div class="flex">
    @include('admin.sidebar')

    <div class="grid place-items-center w-full p-2">
        <form class="grid place-items-center mb-8">
            <div class="flex space-x-2">
                <input class="self-center text-gray-600 dark:text-gray-200 dark:bg-gray-900 border border-gray rounded-md appearance-none h-8 px-2 text-xs leading-tight outline-none" type="text" value="{{ request('search') ?? null }}" name="search" placeholder="Search by name">

                <select class="self-center bg-white dark:bg-gray-800 border border-gray w-full p-2 font-bold text-xs text-gray-600 dark:text-gray-300 rounded-md" name="sort">
                    <option value="" @if ('all' === request('sort')) selected @endif>All</option>
                    @foreach (['lowest', 'highest'] as $sort)
                        <option value="{{ $sort }}" @if ($sort === request('sort')) selected @endif>{{ ucfirst($sort) }}</option>
                    @endforeach
                </select>

                <button class="transition bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 p-2 px-4 text-xs font-bold rounded-md">Filter</button>
            </div>
        </form>
        <div class="px-2 sm:px-4 container mx-auto w-80 sm:w-full lg:max-w-5xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products as $product)
            <div class="bg-white dark:bg-gray-800 border border-gray relative space-y-2">
                <div class="p-2">
                    <div class="space-y-4 mt-2">
                        <img class="object-contain h-20" src="{{ asset($product->image_path) }}" onerror="this.src='{{ config('app.default_product_image_path') }}'" alt="Image"/>
                        <div class="space-y-2">
                            <div class="w-200 break-words truncate flex space-x-1">
                                <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Product ID:</p>
                                <p class="text-xs text-gray-600 dark:text-gray-200 truncate">#{{ $product->id }}</p>
                            </div>
                            <div class="w-200 break-words truncate flex space-x-1">
                                <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Product name:</p>
                                <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $product->name }}</p>
                            </div>
                            <div class="w-200 break-words truncate flex space-x-1">
                                <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Orders count:</p>
                                <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $product->orders_count }}</p>
                            </div>
                            <div class="w-200 break-words truncate flex space-x-1">
                                <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Product price:</p>
                                <p class="text-xs text-current-600 dark:text-current-300 truncate">{{ $product->price }}$</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray p-2">
                    <a
                        class="block w-full text-center font-bold bg-red-100 hover:bg-red-200 text-red-600 dark:text-red-300 dark:bg-red-800 dark:hover:bg-red-900 transition text-xs py-1 px-2 rounded-sm"
                        href="{{ route('product.delete', $product->id) }}"
                    >Delete</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="w-full max-w-5xl px-4 mb-8 mt-8">
            {{ $products->appends(['sort' => $sort, 'search' => $search])->links() }}
        </div>
    </div>
</div>
@stop