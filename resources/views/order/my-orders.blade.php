@extends('layouts.main')
@section('title', "My orders")

@section('content')
    @include('layouts.navbar')

    <div class="px-2 h-8 mt-8 grid place-items-center">
        <form class="self-center flex space-x-2 mb-8">
            <select class="self-center bg-white dark:bg-gray-800 border border-gray w-full p-2 font-bold text-xs text-gray-600 dark:text-gray-300 rounded-md" name="filter">
                <option value="" @if ('all' === request('filter')) selected @endif>All orders except canceled and rejected ones</option>
                @foreach (['pending', 'dispatched', 'shipped', 'delivered', 'rejected', 'canceled'] as $status)
                    <option value="{{ $status }}" @if ($status === request('filter')) selected @endif>{{ ucfirst($status) }}</option>
                @endforeach
            </select>

            <button class="self-center transition bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 p-2 px-4 text-xs font-bold rounded-md">Filter</button>
        </form>
    </div>

    <div class="justify-center flex flex-wrap">
        <div class="grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 px-2 sm:px-4 py-12 grid gap-4 place-items-center max-w-5xl justify-center">
            @foreach ($orders as $order)
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
                            'blue';
                            $percentage = 0;
                    }
                @endphp
    
                <div class="w-full dark:bg-gray-800 border border-gray rounded-sm space-y-2">
                    <div class="grid space-y-2">
                        <div class="flex space-x-2">
                            <div class="w-full flex flex-wrap align-items-center">
                                <div class="border-b border-gray p-2 w-full">
                                    <div class="w-full bg-gray-100 dark:bg-gray-600 rounded-md">
                                        <div class="@if($statusColor === 'yellow') bg-yellow-400 @else bg-{{$statusColor}}-600 @endif text-white dark:bg-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-100 text-xs font-bold  text-center p-0.5 leading-none rounded-md" id="progressBar" style="width: {{ $percentage }}%; transition: 2s ease-out">{{ $percentage }}%</div>
                                    </div>
                                </div>
                                <div class="w-full space-y-2 px-2 py-2">
                                    <div class="break-words truncate flex space-x-1">
                                        <p class="text-gray-600 dark:text-gray-400 text-xs font-bold">Order ID:</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-200 truncate">#{{ $order->id }}</p>
                                    </div>
                                    <div class="break-words flex space-x-1">
                                        <p class="text-gray-600 dark:text-gray-400 text-xs font-bold">Created at:</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-200 break-words">{{ $order->created_at }}</p>
                                    </div>
                                    <div>
                                        <p class="text-{{ $statusColor }}-500 inline text-xs font-bold" id="statusText">{{ $order->status }}</p>
                                    </div>
                                </div>
                                <div class="p-2 border-t border-gray w-full">
                                    <a
                                        class="block w-full text-center font-bold transition bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-xs py-1 px-2 rounded-sm"
                                        href="{{ route('order.track-view', $order->token) }}"
                                    >View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-full max-w-5xl px-4">
            {{ $orders->appends(['filter' => $filter])->links() }}
        </div>
    </div>
    @include('layouts.footer')
@stop
