@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')
<div class="flex">
    @include('dispatcher.sidebar')
    
    <div class="px-2 py-2 px-2 sm:px-4 container mx-auto lg:max-w-4xl space-y-2">
        <form class="grid place-items-center mb-8">
            <div class="ml-16 md:ml-0 w-200 sm:w-80 flex space-x-2">
                <select class="self-center bg-white dark:bg-gray-800 border border-gray w-full p-2 font-bold text-xs text-gray-600 dark:text-gray-300 rounded-md" name="filter">
                    <option value="" @if ('all' === request('filter')) selected @endif>All</option>
                    @foreach (['pending', 'dispatched', 'shipped'] as $status)
                        <option value="{{ $status }}" @if ($status === request('filter')) selected @endif>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>

                <button class="transition bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 p-2 px-4 text-xs font-bold rounded-md">Filter</button>
            </div>
        </form>

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
                        $percentage = 10;
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
            <div class="dark:bg-gray-800 border border-gray rounded-sm space-y-2">
                <div class="border-b border-gray p-2">
                    <div class="w-full bg-gray-100 dark:bg-gray-600 rounded-md">
                        <div class="@if($statusColor === 'yellow') bg-yellow-400 @else bg-{{$statusColor}}-600 @endif text-white dark:bg-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-100 text-xs font-bold  text-center p-0.5 leading-none rounded-md" id="progressBar" style="width: {{ $percentage }}%; transition: 2s ease-out">{{ $percentage }}%</div>
                    </div>
                </div>

                <div class="grid space-y-2">
                    <div class="flex space-x-2 p-2">
                        <div class="div flex flex-wrap align-items-center">
                            <div class="space-y-2">
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Order ID:</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 truncate">#{{ $order->id }}</p>
                                </div>
                                <div class="w-200 break-words truncate">
                                    <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Customer's fullname</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $customer->fullname }}</p>
                                </div>
                                <div class="w-200 break-words">
                                    <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Customer's phone number</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $customer->phone_number }}</p>
                                </div>
                                <div class="w-6/6 break-words">
                                    <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Delivery address</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 break-words">{{ $customer->address }}</p>
                                </div>
                                @if (isset($order->dispatcher))
                                    <div>
                                        <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Dispatcher</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $order->dispatcher->fullname }} &nbsp;|&nbsp; {{ $order->dispatcher->phone_number }}</p>
                                    </div>
                                @endif
                                @if (isset($order->deliveryDriver))
                                    <div>
                                        <p class="font-bold text-gray-600 dark:text-gray-400 text-xs">Delivery Driver</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $order->deliveryDriver->fullname }} &nbsp;|&nbsp; {{ $order->deliveryDriver->phone_number }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold inline text-xs text-{{ $statusColor }}-500">{{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ('pending' === $order->status)
                    <div class="flex space-x-2 border-t border-gray p-2">
                        <a
                            class="w-100 text-center font-bold bg-lime-100 hover:bg-lime-200 text-lime-600 dark:text-lime-300 dark:bg-lime-800 dark:hover:bg-lime-900 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.dispatch-view', $order->id) }}"
                        >Dispatch</a>
                        <a
                            class="w-100 text-center font-bold bg-red-100 hover:bg-red-200 text-red-600 dark:text-red-300 dark:bg-red-800 dark:hover:bg-red-900 text-xs py-1 px-2 rounded-sm"
                            href="{{ route('order.reject', $order->id) }}"
                        >Reject</a>
                    </div>
                @endif
            </div>
            @endif
        @endforeach
        
        <div class="w-full max-w-4xl mb-8 mt-8">
            {{ $orders->appends(['filter' => $filter])->links() }}
        </div>
    </div>
</div>
@stop