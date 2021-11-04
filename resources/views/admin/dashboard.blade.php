@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="p-4 w-full lg:w-4/5 xl:w-3/5 grid grid-cols-1 sm:grid-cols-2 gap-4 h-screen place-items-center">
                <div class="bg-white dark:bg-gray-800 h-80 w-full relative rounded-sm border border-gray">
                    <div class="border-b border-gray py-4 text-center text-gray-600 dark:text-gray-30 text-xl font-bold">Members</div>

                    <div class="h-3/5 grid place-items-center">
                        <span class="self-center block text-6xl text-gray-600 dark:text-gray-300 font-bold">{{ $membersCount }}</span>
                    </div>

                    <div class="border-t border-gray bottom-0 left-0 p-2 absolute w-full">
                        <a href="{{ route('members') }}"><div class="w-full text-xs bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 transition rounded-sm p-2 text-center font-bold">View Members</div></a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 h-80 w-full relative rounded-sm border border-gray">
                    <div class="border-b border-gray py-4 text-center text-gray-600 dark:text-gray-30 text-xl font-bold">Products</div>

                    <div class="h-3/5 grid place-items-center">
                        <span class="self-center block text-6xl text-gray-600 dark:text-gray-300 font-bold">{{ $productsCount }}</span>
                    </div>

                    <div class="border-t border-gray bottom-0 left-0 p-2 absolute w-full">
                        <a href="{{ route('products') }}"><div class="w-full text-xs bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 transition rounded-sm p-2 text-center font-bold">View Products</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop