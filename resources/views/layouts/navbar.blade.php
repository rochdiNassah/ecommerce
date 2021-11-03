<div class="border-b border-gray dark:bg-gray-800">
    <div class="px-2 sm:px-4 h-14 flex justify-between container mx-auto lg:max-w-5xl">
        <div class="logo flex">
            <a class="transition self-center w-8 h-8 mr-2 bg-current-200 hover:bg-current-300 dark:bg-current-300 dark:hover:bg-current-400 rounded-full" href="{{ route('home') }}"></a>
            <p class="hidden sm:block self-center text-current-600 dark:text-current-400 text-sm font-bold">{{ config('app.name') }}</p>
        </div>

        @if ('home' === Route::current()->action['as'])
        <form class="self-center">
            <input class="w-40 sm:w-64 md:w-400 h-10 text-gray-600 bg-white dark:bg-gray-800 border border-gray rounded-full appearance-none p-3 text-sm leading-tight outline-none" type="text" value="{{ request('search') ?? null }}" name="search" placeholder="What are you looking for?">
        </form>
        @endif

        <div class="flex space-x-8">
            <a
                class="transition bg-current-100 hover:bg-current-200 text-current-600 dark:bg-current-800 dark:hover:bg-current-900 dark:text-current-300 p-2 px-4 self-center text-xs font-bold rounded-sm"
                href="{{ route('order.request-my-orders-view') }}"
            >My Orders</a>
        </div>
    </div>
</div>