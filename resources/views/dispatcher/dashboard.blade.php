@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<div class="flex">
    @include('dispatcher.sidebar')

    <div class="px-4 py-12 sm:px-8 md:px-16 lg:px-32 w-full grid grid-cols-1 gap-4 place-items-center">
        @foreach ($orders as $order)
            @php
                $customer = (object) json_decode($order->customer);
                $statusColor = $order->status === 'rejected' ? 'red' : ($order->status === 'pending' ? 'yellow': ($order->status === 'delivered' ? 'green' : 'blue'));
                $percentage = $order->status === 'pending' ? 0 : ($order->status === 'dispatched' ? 30 : ($order->status === 'shipped' ? 60 : ($order->status === 'delivered' ? 100 : 0)));
                $authorized = true;

                if (isset($order->dispatcher)) {
                    $authorized = ('pending' !== $order->status && Auth::id() === $order->dispatcher->id);
                }
            @endphp

            @if ($authorized)
            <div class="w-full sm:w-5/5 lg:w-4/5 bg-dark border border-gray rounded-sm space-y-2">
                <div class="border-b border-gray p-2">
                    <div class="w-full bg-gray-600 rounded-md">
                        <div class="bg-{{ $mainColor }}-600 text-xs font-medium text-{{ $mainColor }}-100 text-center p-0.5 leading-none rounded-md" style="width: {{ $percentage }}%">{{ $percentage }}%</div>
                    </div>
                </div>

                <div class="grid space-y-2">
                    <div class="flex space-x-2 p-2">
                        <div class="relative p-2 border border-gray rounded-sm min-w-max w-32 h-20">
                            <img class="object-contain w-full h-full" src="{{ $order->product->image_path }}" alt="Image"/>
                            <div class="absolute -top-1 -right-1 rounded-xl px-2 py-1 text-center text-{{ $mainColor }}-900 bg-{{ $mainColor }}-100 font-bold text-xs truncate">{{ $order->product->price }}$</div>
                        </div>
                        <div class="div flex flex-wrap align-items-center">
                            <div class="space-y-2">
                                <div>
                                    <p class="text-gray-400 text-xs">Customer's fullname</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->fullname }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Customer's phone number</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $customer->phone_number }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Customer's delivery address</p>
                                    <p class="text-xs font-bold text-gray-200">{{ $customer->address }}</p>
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
                                @if ('pending' === $order->status)
                                    <div class="flex space-x-2">
                                        <a
                                            class="w-full text-center font-bold bg-lime-800 hover:bg-lime-900 transition text-lime-300 text-xs py-1 px-2 rounded-sm"
                                            href="{{ route('order.dispatch-view', $order->id) }}"
                                        >Dispatch</a>
                                        <a
                                            class="w-full text-center font-bold bg-orange-800 hover:bg-orange-900 transition text-orange-300 text-xs py-1 px-2 rounded-sm"
                                            href="{{ route('order.reject', $order->id) }}"
                                        >Reject</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@stop