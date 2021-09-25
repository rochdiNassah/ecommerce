@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
    <div class="flex">
        <div class="border-r border-gray-500 w-80 h-screen bg-gray-800 sidebar relative">
            <div class="border-b border-gray-500 p-4 px-8 grid place-items-center">
                <img class="inline object-cover w-16 h-16 mb-2 rounded-full" src="{{ asset('images/default-avatar.png') }}" alt="Avatar"/>
                <h1 class="text-lg text-center text-gray-200 font-bold">{{ strtoupper(Auth::user()->fullname) }}</h1>
            </div>

            <ul class="mt-40 ml-8">
                <li class="my-4">
                    <a class="transition text-gray-300 hover:text-gray-100 text-2xl" href="#">Users</a>
                </li>

                <li class="my-4">
                    <a class="transition text-gray-300 hover:text-gray-100 text-2xl" href="#">Orders</a>
                </li>

                <li class="my-4">
                    <a class="transition text-gray-300 hover:text-gray-100 text-2xl" href="#">My Account</a>
                </li>
            </ul>

            <div class="border-t border-gray-500 bottom-0 left-0 p-4 px-8 absolute w-full">
                <a href="/logout"><div class="w-full text-sm text-red-300 bg-red-500 hover:bg-red-600 transition rounded-sm p-2 text-center font-bold">Log Out</div></a>
            </div>
        </div>
    </div>
@stop