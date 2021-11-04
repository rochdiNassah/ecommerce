@extends('layouts.main')
@section('title', "Track #{$order->id}")

@section('content')
    @include('layouts.navbar')
        <h1 class="text-center font-bold text-gray-600 dark:text-gray-200 text-xl sm:text-2xl my-8">Track your order's status in real-time!</h1>

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

        <div class="px-2 sm:px-4 container mx-auto lg:max-w-5xl">
            <div class="dark:bg-gray-800 border border-gray rounded-sm space-y-2">
                <div class="border-b border-gray p-2">
                    <div class="w-full bg-gray-100 dark:bg-gray-600 rounded-md">
                        <div class="@if($statusColor === 'yellow') bg-yellow-400 @else bg-{{$statusColor}}-600 @endif text-white dark:bg-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-100 text-xs font-bold  text-center p-0.5 leading-none rounded-md" id="progressBar" style="width: {{ $percentage }}%; transition: 2s ease-out">{{ $percentage }}%</div>
                    </div>
                </div>

                <div class="mx-2 relative p-2 border border-gray rounded-sm min-w-max w-40 h-32">
                    <img class="object-contain w-full h-full" src="{{ asset($order->product->image_path) }}" alt="Image"/>
                    <div class="absolute -top-1 -right-1 rounded-xl px-2 py-1 text-center text-current-600 bg-current-100 font-bold text-xs truncate">{{ $order->product->price }}$</div>
                </div>

                <div class="grid space-y-2">
                    <div class="flex space-x-2 px-2">
                        <div class="div flex flex-wrap align-items-center">
                            <div class="space-y-2">
                                <div class="w-200 break-words truncate flex space-x-1">
                                    <p class="text-gray-600 dark:text-gray-400 text-xs font-bold">Order ID:</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 truncate">#{{ $order->id }}</p>
                                </div>
                                <div class="w-200 break-words truncate">
                                    <p class="text-gray-600 dark:text-gray-400 text-xs font-bold">Full Name</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $customer->fullname }}</p>
                                </div>
                                <div class="w-200 break-words">
                                    <p class="text-gray-600 dark:text-gray-400 text-xs font-bold">Phone number</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 truncate">{{ $customer->phone_number }}</p>
                                </div>
                                <div class="w-6/6 break-words">
                                    <p class="text-gray-600 dark:text-gray-400 text-xs font-bold">Delivery address</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-200 break-words">{{ $customer->address }}</p>
                                </div>
                                <div>
                                    <p class="text-{{ $statusColor }}-500 inline text-xs font-bold" id="statusText">{{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="actions">
                    @if (!in_array($order->status, ['delivered', 'rejected', 'canceled']))
                        <div class="flex space-x-2 border-t border-gray p-2">
                            <a
                                class="w-32 text-center font-bold bg-red-100 hover:bg-red-200 text-red-600 dark:text-red-300 dark:bg-red-800 dark:hover:bg-red-900 text-xs py-1 px-2 rounded-sm"
                                href="{{ route('order.cancel', $order->token) }}"
                                id="cancelOrder"
                            >Cancel Order</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid place-items-center my-8">
            <a
                class="w-32 rounded-sm transition text-center bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-xs py-2 px-4 font-bold"
                href="{{ route('order.my-orders', ['email' => $customer->email, 'token' => $order->token]) }}"
            >View all orders</a>
        </div>

        <script src="{{ asset('js/autobahn.js') }}"></script>
        <script>
            var WsUri = {!! json_encode($ws_uri) !!}
            var orderStatus = {
                rejected: ['red', '100%'],
                canceled: ['red', '100%'],
                pending: ['yellow', '10%'],
                dispatched: ['blue', '30%'],
                shipped: ['lime', '60%'],
                delivered: ['green', '100%']
            }
            var token = null
            var orderStatusLayout = null
            var progressBarElement = document.getElementById('progressBar')
            var statusTextElement = document.getElementById('statusText')
            var cardActionsElement = document.getElementById('actions')

            var conn = new ab.Session(WsUri,
                function() {
                    token = window.location.href.split('/')[window.location.href.split('/').length-1]
                    conn.subscribe(token, function(order, data) {
                        orderStatusLayout = orderStatus[data.status]

                        progressBarElement.innerText = orderStatusLayout[1]
                        progressBarElement.classList.replace(progressBarElement.classList[0], 'bg-'+orderStatusLayout[0]+'-500')
                        progressBarElement.style.width = orderStatusLayout[1]

                        statusTextElement.innerText = data.status
                        statusTextElement.classList.replace(statusTextElement.classList[0], 'text-'+orderStatusLayout[0]+'-600')
                    
                        if ('100%' === orderStatusLayout[1]) {
                            if (document.getElementById('cancelOrder')) {
                                addClass(document.getElementById('cancelOrder'), ['transition', 'opacity-0'])
                                addClass(cardActionsElement, ['transition', 'opacity-0'])
                                setTimeout(() => {
                                    cardActionsElement.remove();
                                }, 200);
                            }
                        }
                    });
                },
                function() {
                    console.warn('WebSocket connection closed');
                },
                {'skipSubprotocolCheck': true}
            );
        </script>
    @include('layouts.footer')
@stop