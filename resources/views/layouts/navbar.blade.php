<div class="border-b border-gray bg-dark">
    <div class="px-2 sm:px-4 h-14 flex justify-between container mx-auto lg:max-w-6xl">
        <div class="logo flex">
            <a class="transition self-center w-8 h-8 mr-2 bg-{{ $mainColor }}-300 hover:bg-{{ $mainColor }}-400 rounded-full" href="{{ route('home') }}"></a>
            <p class="hidden sm:block self-center text-{{ $mainColor }}-400 text-sm font-bold">{{ config('app.name') }}</p>
        </div>

        @if ('home' === Route::current()->action['as'])
        <form class="self-center">
            <input class="w-40 sm:w-64 md:w-400 h-10 text-gray-200 bg-gray-900 border border-gray rounded-full appearance-none p-3 text-sm leading-tight outline-none" type="text" value="{{ request('search') ?? null }}" name="search" placeholder="What are you looking for?">
        </form>
        @endif

        <div class="flex space-x-8">
            <a
                class="transition bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-{{ $mainColor }}-300 p-2 px-4 self-center text-xs font-bold rounded-sm"
                href="{{ route('order.request-my-orders-view') }}"
            >My Orders</a>
            <a
                class="hidden sm:block transition bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-{{ $mainColor }}-300 p-2 px-4 self-center text-xs font-bold rounded-sm"
                href="{{ route('login') }}"
            >Log In</a>
        </div>
    </div>
</div>