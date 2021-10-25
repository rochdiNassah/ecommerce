@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<div class="flex">
    @include('dispatcher.sidebar')

    <div class="px-4 py-12 sm:px-8 md:px-16 lg:px-32 w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($orders as $order)
            @php
                $customer = (object) json_decode($order->customer);
                $statusColor = $order->status === 'rejected' ? 'red' : ($order->status === 'pending' ? 'yellow': ($order->status === 'dispatched' ? 'green' : 'blue'));
            @endphp

            <div class="w-full bg-dark p-2 border border-gray rounded-sm space-y-2">
                <p class="font-bold rounded-sm text-xs text-{{ $mainColor }}-300 bg-{{ $mainColor }}-800 p-2 text-center">Order #{{ $order->id }}</p>

                <div class="grid space-y-2">
                    <div class="flex space-x-2">
                        <div class="relative p-2 border border-gray rounded-sm min-w-max w-32 h-20">
                            <img class="object-contain w-full h-full" src="{{ $order->product->image_path }}" alt="Image"/>
                            <div class="absolute -top-1 -right-1 rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-900 bg-{{ $mainColor }}-100 font-bold text-xs truncate">{{ $order->product->price }}$</div>
                        </div>
                        <div class="div flex flex-wrap align-items-center">
                            <div class="space-y-2">
                                <div class="w-32">
                                    <p class="text-gray-400 text-xs">Full Name</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->fullname }}</p>
                                </div>
                                <div class="w-32">
                                    <p class="text-gray-400 text-xs">Phone Number</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->phone_number }}</p>
                                </div>
                                <div class="w-32">
                                    <p class="text-gray-400 text-xs">Address</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->address }}</p>
                                </div>
                                <div class="w-32">
                                    <p class="text-gray-400 text-xs">Dispatcher</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $order->dispatcher->fullname ?? 'Not dispatched yet' }}</p>
                                </div>
                                <div class="w-32">
                                    <p class="text-gray-400 text-xs">Delivery Driver</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $order->deliveryDriver->fullname ?? 'Not dispatched yet' }}</p>
                                </div>
                                <div>
                                    <p class="inline text-xs font-bold text-{{ $statusColor }}-600">{{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between space-x-2">
                    <a
                        class="w-full text-center font-bold bg-green-800 hover:bg-green-900 transition text-green-300 text-xs py-1 px-2 rounded-sm"
                        href="{{ route('order.dispatch-view', $order->id) }}"
                    >Dispatch</a>
                    <a
                        class="w-full text-center font-bold bg-red-800 hover:bg-red-900 transition text-red-300 text-xs py-1 px-2 rounded-sm"
                        href="{{ route('order.reject', $order->id) }}"
                    >Reject</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@stop