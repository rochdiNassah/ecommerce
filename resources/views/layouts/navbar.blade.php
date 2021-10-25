<div class="border-b border-gray bg-dark">
    <div class="px-2 sm:px-4 h-14 flex justify-between container mx-auto lg:max-w-7xl">
        <div class="logo flex">
            <a class="transition self-center w-8 h-8 mr-2 bg-{{ $mainColor }}-300 hover:bg-{{ $mainColor }}-400 rounded-full" href="{{ route('home') }}"></a>
            <p class="self-center text-{{ $mainColor }}-400 text-sm font-bold">{{ config('app.name') }}</p>
        </div>

        <div class="flex space-x-8">
            <a
                class="transition bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-{{ $mainColor }}-300 p-2 px-4 self-center text-xs font-bold rounded-sm"
                href="{{ route('login') }}"
            >Log In</a>

            <a
                class="transition bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-{{ $mainColor }}-300 p-2 px-4 self-center text-xs font-bold rounded-sm hidden sm:block"
                href="{{ route('join') }}"
            >Join</a>
        </div>
    </div>
</div>