@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="p-4 w-full lg:w-4/5 xl:w-3/5 grid grid-cols-1 sm:grid-cols-2 gap-4 h-screen place-items-center">
                <div class="bg-gray h-80 w-full relative rounded-sm border border-gray">
                    <div class="border-b border-gray py-4 text-center text-gray-200 text-xl font-bold">Users</div>

                    <div class="h-3/5 grid place-items-center">
                        <span class="self-center block text-6xl text-gray-200 font-bold">{{ $usersCount }}</span>
                    </div>

                    <div class="border-t border-gray bottom-0 left-0 p-4 px-8 absolute w-full">
                        <a href="{{ route('users') }}"><div class="w-full text-sm text-{{ $mainColor }}-400 bg-{{ $mainColor }}-900 hover:bg-{{ $mainColor }}-800 transition rounded-sm p-2 text-center font-bold">View All Users</div></a>
                    </div>
                </div>

                <div class="bg-gray h-80 w-full relative rounded-sm border border-gray">
                    <div class="border-b border-gray py-4 text-center text-gray-200 text-xl font-bold">Products</div>

                    <div class="h-3/5 grid place-items-center">
                        <span class="self-center block text-6xl text-gray-200 font-bold">{{ $productsCount }}</span>
                    </div>

                    <div class="border-t border-gray bottom-0 left-0 p-4 px-8 absolute w-full">
                        <a href="{{ route('products') }}"><div class="w-full text-sm text-{{ $mainColor }}-400 bg-{{ $mainColor }}-900 hover:bg-{{ $mainColor }}-800 transition rounded-sm p-2 text-center font-bold">View All Products</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop