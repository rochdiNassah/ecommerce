@extends('layouts.main')
@section('title', "My orders")

@section('content')
    @include('layouts.navbar')

    <div class="h-8 mt-8 grid place-items-center">
        <form class="self-center flex space-x-2 mb-8">
            <select class="self-center bg-lightdark border border-gray w-full p-3 font-bold text-xs text-gray-300 rounded-md" name="filter">
                <option value="" @if ('all' === request('filter')) selected @endif>All except canceled and rejected ones</option>
                @foreach (['pending', 'dispatched', 'shipped', 'delivered', 'rejected', 'canceled'] as $status)
                    <option value="{{ $status }}" @if ($status === request('filter')) selected @endif>{{ ucfirst($status) }}</option>
                @endforeach
            </select>

            <button class="self-center transition bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-{{ $mainColor }}-300 p-2 px-4 text-md font-bold rounded-md">Filter</button>
        </form>
    </div>

    <div class="grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 px-4 py-12 sm:px-8 md:px-16 lg:px-32 grid gap-4 place-items-center">
        @foreach ($orders as $order)
            @php
                $customer = (object) json_decode($order->customer);

                switch($order->status) {
                    case 'rejected':
                    case 'canceled':
                        $statusColor = 'red';
                        break;
                    case 'pending':
                        $statusColor = 'yellow';
                        break;
                    case 'dispatched':
                        $statusColor = 'blue';
                        break;
                    case 'shipped':
                        $statusColor = 'lime';
                        break;
                    case 'delivered':
                        $statusColor = 'green';
                        break;
                    default:
                        'blue';
                }
            @endphp

            <div class="w-full bg-dark border border-gray rounded-sm space-y-2">
                <div class="grid space-y-2">
                    <div class="flex space-x-2 p-2">
                        <div class="w-full flex flex-wrap align-items-center">
                            <div class="w-full space-y-2">
                                <div class="break-words truncate flex space-x-1">
                                    <p class="text-gray-400 text-xs">Order ID:</p>
                                    <p class="text-xs font-bold text-gray-200 truncate">{{ $order->id }}</p>
                                </div>
                                <div class="break-words">
                                    <p class="text-gray-400 text-xs">Created at</p>
                                    <p class="text-xs font-bold text-gray-200 break-words">{{ $order->created_at }}</p>
                                </div>
                                <div>
                                    <p class="text-{{ $statusColor }}-500 inline text-xs font-bold" id="statusText">{{ $order->status }}</p>
                                </div>
                                <a
                                    class="block w-full text-center font-bold bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 transition text-{{ $mainColor }}-300 text-xs py-1 px-2 rounded-sm"
                                    href="{{ route('order.track-view', $order->token) }}"
                                >View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="grid place-items-center mb-10">
        {{ $orders->appends(['filter' => $filter])->links() }}
    </div>
    @include('layouts.footer')
@stop