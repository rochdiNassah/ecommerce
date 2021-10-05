@extends('layouts.main')
@section('title', 'Users')

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="p-4 w-full sm:w-4/5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($products as $product)
                <div class="bg-gray border border-gray rounded-sm p-4 relative">
                    <div class="flex space-x-4">
                        <div class="text-center grid place-items-center space-y-1">
                            <img class="h-12 w-12" src="{{ asset($product->image_path) }}" alt="Image"/>
                            <div class="rounded-sm text-gray-200">{{ $product->price }}$</div>
                        </div>
    
                        <div class="flex-1 space-y-2 py-1">
                            <div class="self-center px-2 rounded-sm w-40 text-gray-200 font-bold text-sm truncate">{{ $product->name }}</div>
                            <div class="self-center px-2 rounded-sm w-40 text-gray-300 font-bold text-xs truncate">{{ $product->description }}</div>
                            
                            <div class="grid grid-cols-2">
                                <a
                                    class="text-center font-bold bg-blue-900 hover:bg-blue-800 transition text-blue-400 text-xs py-1 px-2 rounded-sm mx-2"
                                    href="{{ route('user.update-role-screen', $product->id) }}"
                                >Edit</a> 
                                <a
                                    class="text-center font-bold bg-red-900 hover:bg-red-800 transition text-red-400 text-xs py-1 px-2 rounded-sm mx-2"
                                    href="{{ route('user.delete', $product->id) }}"
                                >Delete</a> 
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop