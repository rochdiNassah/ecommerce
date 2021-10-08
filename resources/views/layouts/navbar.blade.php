<div class="p-2 h-16 border-b border-gray flex justify-around">
    <div class="logo flex">
        <a class="transition self-center w-10 h-10 mr-2 bg-{{ $mainColor }}-200 hover:bg-{{ $mainColor }}-500 rounded-full" href="{{ route('home') }}"></a>
        <p class="self-center text-{{ $mainColor }}-400 text-md font-bold">{{ config('app.name') }}</p>
    </div>

    <div class="flex space-x-8">
        <a
            class="transition bg-{{ $mainColor }}-200 hover:bg-{{ $mainColor }}-300 text-{{ $mainColor }}-700 p-2 px-4 self-center text-sm font-bold rounded-sm"
            href="{{ route('login') }}"
        >Log In</a>

        <a
            class="transition bg-{{ $mainColor }}-200 hover:bg-{{ $mainColor }}-300 text-{{ $mainColor }}-700 p-2 px-4 self-center text-sm font-bold rounded-sm hidden sm:block"
            href="{{ route('join') }}"
        >Join</a>
    </div>
</div>