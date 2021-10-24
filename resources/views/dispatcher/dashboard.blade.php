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

                <div class="relative bg-white border border-gray p-2 space-y-2 rounded-sm">                
                    <p class="text-sm text-gray-600"><span class="text-xs font-bold">Fullname: </span>{{ $customer_details->fullname }}</p>
                    <p class="text-sm text-gray-600"><span class="text-xs font-bold">Email: </span>{{ $customer_details->email }}</p>
                    <p class="text-sm text-gray-600"><span class="text-xs font-bold">Phone Number: </span>{{ $customer_details->phone_number }}</p>
                    <p class="text-sm text-gray-600"><span class="text-xs font-bold">Address: </span>{{ $customer_details->address }}</p>
                    <p class="text-sm text-gray-600"><span class="text-xs font-bold">Product: </span>{{ $order->product }}</p>

                    @php
                        $statusColor = $order->status === 'pending' ? 'yellow' : ($order->status === 'canceled' || 'rejected' ? 'red' : ($order->status === 'delivered' ? 'green' : 'blue'))
                    @endphp

                    <span class="absolute top-1 right-2 rounded-sm font-bold text-xs bg-{{ $statusColor }}-100 text-{{ $statusColor }}-600 text-center px-4">{{ $order->status }}</span>
                    
                    <div class="flex space-x-2 justify-between">
                        <a
                            class="text-center font-bold bg-green-100 hover:bg-green-200 transition text-green-600 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.dispatch', $order->id) }}"
                        >Dispatch</a> 
                        <a
                            class="text-center font-bold bg-red-100 hover:bg-red-200 transition text-red-600 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.reject', $order->id) }}"
                        >Reject</a> 
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop