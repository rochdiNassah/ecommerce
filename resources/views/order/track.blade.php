@extends('layouts.main')
@section('title', "Track #{$order->id}")

@section('content')
    @include('layouts.navbar')
        <h1 class="text-center font-bold text-gray-200 text-xl sm:text-2xl mt-8 mb-6">Track your order's status.</h1>

        @php
            $customer = (object) json_decode($order->customer);

            switch($order->status) {
                case 'rejected':
                case 'canceled':
                    $statusColor = 'red';
                    $percentage = 100;
                    break;
                case 'pending':
                    $statusColor = 'yellow';
                    $percentage = 0;
                    break;
                case 'dispatched':
                    $statusColor = 'blue';
                    $percentage = 30;
                    break;
                case 'shipped':
                    $statusColor = 'lime';
                    $percentage = 60;
                    break;
                case 'delivered':
                    $statusColor = 'green';
                    $percentage = 100;
                    break;
                default:
                    'blue';
                    $percentage = 0;
            }
        @endphp

        <div class="flex flex-wrap justify-center mb-16 mt-10">
            <div class="w-full lg:w-800 bg-dark border border-gray rounded-sm space-y-2">
                <div class="border-b border-gray p-2">
                    <div class="w-full bg-gray-600 rounded-md">
                        <div class="bg-{{ $statusColor }}-600 text-xs font-medium text-{{ $statusColor }}-100 text-center p-0.5 leading-none rounded-md" style="width: {{ $percentage }}%">{{ $percentage }}%</div>
                    </div>
                </div>

                <div class="mx-2 relative p-2 border border-gray rounded-sm min-w-max w-40 h-32">
                    <img class="object-contain w-full h-full" src="{{ $order->product->image_path }}" alt="Image"/>
                    <div class="absolute -top-1 -right-1 rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-900 bg-{{ $mainColor }}-100 font-bold text-xs truncate">{{ $order->product->price }}$</div>
                </div>

                <div class="grid space-y-2">
                    <div class="flex space-x-2 p-2">
                        <div class="div flex flex-wrap align-items-center">
                            <div class="space-y-2">
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="text-gray-400 text-xs">Order ID:</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $order->id }}</p>
                                </div>
                                <div class="w-200 break-words truncate">
                                    <p class="text-gray-400 text-xs">Your fullname</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->fullname }}</p>
                                </div>
                                <div class="w-200 break-words">
                                    <p class="text-gray-400 text-xs">Your phone number</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->phone_number }}</p>
                                </div>
                                <div class="w-6/6 break-words">
                                    <p class="text-gray-400 text-xs">Your delivery address</p>
                                    <p class="text-xs font-bold text-gray-200 break-words">{{ $customer->address }}</p>
                                </div>
                                <div>
                                    <p class="inline text-xs font-bold text-{{ $statusColor }}-500">{{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!in_array($order->status, ['delivered', 'rejected', 'canceled']))
                    <div class="flex space-x-2 border-t border-gray p-2">
                        <a
                            class="w-200 text-center font-bold bg-red-800 hover:bg-red-900 transition text-red-300 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.reject', $order->id) }}"
                        >Cancel Your Order</a>
                    </div>
                @endif
            </div>
        </div>
    @include('layouts.footer')
@stop