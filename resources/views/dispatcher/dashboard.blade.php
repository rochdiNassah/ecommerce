@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
    @include('dispatcher.sidebar')

    <div class="pt-16 grid place-items-center w-full">
        <div class="p-2 w-full sm:w-4/5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($orders as $order)
            @php
                $customer_details = (object) json_decode($order->customer_details);
            @endphp

            <div class="bg-white border border-gray p-2 space-y-2">
                <p class="text-center text-md bg-{{ $mainColor }}-100 text-{{ $mainColor }}-600">#{{ $order->id }}</p>
                <p class="text-sm text-gray-600">{{ $customer_details->fullname }}</p>
                <p class="text-sm text-gray-600">{{ $customer_details->email }}</p>
                <p class="text-sm text-gray-600">{{ $customer_details->phone_number }}</p>
                <p class="text-sm text-gray-600">{{ $customer_details->address }}</p>
            
                <div class="flex space-x-2 justify-between">
                    <a
                        class="text-center font-bold bg-green-100 hover:bg-green-200 transition text-green-600 text-xs py-1 px-2 rounded-sm"
                        href="{{ route('user.approve', $order->id) }}"
                    >Dispatch</a> 

                    <a
                        class="text-center font-bold bg-red-100 hover:bg-red-200 transition text-red-600 text-xs py-1 px-2 rounded-sm"
                        href="{{ route('user.delete', $order->id) }}"
                    >Reject</a> 
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop