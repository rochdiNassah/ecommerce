@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<div class="flex">
    @include('dispatcher.sidebar')

    <div class="px-4 py-12 sm:px-8 md:px-16 lg:px-32 w-full grid grid-cols-1 gap-4 place-items-center">
        @foreach ($orders as $order)
            @php
                $customer = (object) json_decode($order->customer);

                switch ($order->status) {
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
                        $statusColor = 'blue';
                        $percentage = 0;
                }

                $authorized = true;

                if (isset($order->dispatcher)) {
                    $authorized = ('pending' !== $order->status && Auth::id() === $order->dispatcher->id);
                }
            @endphp

            @if ($authorized)
            <div class="w-full lg:w-800 bg-dark border border-gray rounded-sm space-y-2">
                <div class="border-b border-gray p-2">
                    <div class="w-full bg-gray-600 rounded-md">
                        <div class="bg-{{ $statusColor }}-600 text-xs font-medium text-{{ $statusColor }}-100 text-center p-0.5 leading-none rounded-md" style="width: {{ $percentage }}%">{{ $percentage }}%</div>
                    </div>
                </div>

                <div class="grid space-y-2">
                    <div class="flex space-x-2 p-2">
                        {{-- <div class="relative p-2 border border-gray rounded-sm min-w-max w-32 h-20">
                            <img class="object-contain w-full h-full" src="{{ $order->product->image_path }}" alt="Image"/>
                            <div class="absolute -top-1 -right-1 rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-900 bg-{{ $mainColor }}-100 font-bold text-xs truncate">{{ $order->product->price }}$</div>
                        </div> --}}
                        <div class="div flex flex-wrap align-items-center">
                            <div class="space-y-2">
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <a class="text-red-600" href="{{ route('order.track-view', $order->token) }}">Track Order</a>
                                </div>
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="text-gray-400 text-xs">Order ID:</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $order->id }}</p>
                                </div>
                                <div class="w-200 break-words truncate">
                                    <p class="text-gray-400 text-xs">Customer's fullname</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->fullname }}</p>
                                </div>
                                <div class="w-200 break-words">
                                    <p class="text-gray-400 text-xs">Customer's phone number</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->phone_number }}</p>
                                </div>
                                <div class="w-6/6 break-words">
                                    <p class="text-gray-400 text-xs">Customer's delivery address</p>
                                    <p class="text-xs font-bold text-gray-200 break-words">{{ $customer->address }}</p>
                                </div>
                                @if (isset($order->dispatcher))
                                    <div>
                                        <p class="text-gray-400 text-xs">Dispatcher</p>
                                        <p class="text-xs font-bold text-gray-200 truncate">{{ $order->dispatcher->fullname }} &nbsp;|&nbsp; {{ $order->dispatcher->phone_number }}</p>
                                    </div>
                                @endif
                                @if (isset($order->deliveryDriver))
                                    <div>
                                        <p class="text-gray-400 text-xs">Delivery Driver</p>
                                        <p class="text-xs font-bold text-gray-200 truncate">{{ $order->deliveryDriver->fullname }} &nbsp;|&nbsp; {{ $order->deliveryDriver->phone_number }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="inline text-xs font-bold text-{{ $statusColor }}-500">{{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ('pending' === $order->status)
                    <div class="flex space-x-2 border-t border-gray p-2">
                        <a
                            class="w-100 text-center font-bold bg-lime-800 hover:bg-lime-900 transition text-lime-300 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.dispatch-view', $order->id) }}"
                        >Dispatch</a>
                        <a
                            class="w-100 text-center font-bold bg-orange-800 hover:bg-orange-900 transition text-orange-300 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.reject', $order->id) }}"
                        >Reject</a>
                    </div>
                @endif
            </div>
            @endif
        @endforeach
    </div>
</div>
@stop