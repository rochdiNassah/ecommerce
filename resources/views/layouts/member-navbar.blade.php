<div class="border-b border-gray">
    <div class="px-2 sm:px-4 h-14 flex justify-between container mx-auto lg:max-w-7xl">
        <div class="logo flex">
            <div class="transition self-center w-8 h-8 mr-2 rounded-full"><img src="{{ Auth::user()->avatar_path }}" alt="Avatar"></div>
            <p class="self-center text-{{ $mainColor }}-400 text-sm font-bold">{{ Auth::user()->fullname }}</p>
        </div>

        <div class="flex space-x-8">
            <a
                class="transition bg-red-100 hover:bg-red-200 text-red-600 p-2 px-4 self-center text-xs font-bold rounded-sm"
                href="{{ route('logout') }}"
            >Log Out</a>
        </div>
    </div>
</div>