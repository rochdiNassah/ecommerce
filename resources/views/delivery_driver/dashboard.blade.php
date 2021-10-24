@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<div class="flex">
    @include('delivery_driver.sidebar')

    <div class="pt-16 grid place-items-center w-full">
        <div class="p-2 w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($orders as $order)
                @php
                    $customer_details = (object) json_decode($order->customer_details);
                @endphp
                @for ($i = 0; $i < 50; $i++)
                    
                <div class="relative bg-white border border-gray p-2 space-y-2 rounded-sm">
                    <p class="bg-blue-100 text-blue-600 text-sm py-1 text-center">Order #{{ $order->id }}</p>

                    <div>
                        <span class="font-bold bg-gray-100 text-gray-600 px-4 py-1 text-xs">Customer details</span>
                        <div class="px-4 py-1">
                            <p class="text-sm text-gray-600"><span class="text-xs font-bold">FullName: </span>{{ $customer_details->fullname }}</p>
                            <p class="text-sm text-gray-600"><span class="text-xs font-bold">Phone Number: </span>{{ $customer_details->phone_number }}</p>
                            <p class="text-sm text-gray-600"><span class="text-xs font-bold">Address: </span>{{ $customer_details->address }}</p>
                        </div>
                    </div>
                    
                    @php
                        $statusColor = $order->status === 'pending' ? 'yellow' : ($order->status === 'canceled' || 'rejected' ? 'red' : ($order->status === 'delivered' ? 'green' : 'blue'))
                    @endphp
                    
                    <div class="mt-4">
                        <span class="font-bold bg-gray-100 text-gray-600 px-4 py-1 text-xs">Order details</span>
                        <div class="px-4 py-1">
                            <p class="text-sm text-gray-600"><span class="text-xs font-bold">Dispatcher: </span>Foobar</p>
                            <p class="text-sm text-gray-600"><span class="text-xs font-bold">Product Name: </span>Camera</p>
                            <p class="inline px-2 py-1 font-bold rounded-sm text-xs text-{{ $statusColor }}-600 bg-{{ $statusColor }}-100">{{ $order->status }}</p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2 justify-between">
                        <a
                            class="text-center w-full font-bold bg-blue-100 hover:bg-blue-200 transition text-blue-600 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.update', $order->id) }}"
                        >Update</a> 
                    </div>
                </div>
                @endfor
            @endforeach
        </div>
    </div>
</div>
@stop