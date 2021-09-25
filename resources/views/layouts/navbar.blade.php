<div class="p-2 bg-gray-800 h-16 border-b border-gray-500 flex justify-around">
    <div class="logo flex">
        <a class="transition self-center w-10 h-10 mr-2 bg-gray-700 hover:bg-gray-600 rounded-full" href="/"></a>
        <p class="self-center text-gray-200 text-md font-bold">{{ config('app.name') }}</p>
    </div>

    <div class="flex space-x-8">
        <a class="transition bg-green-700 hover:bg-green-600 text-green-300 p-2 px-4 self-center font-bold text-sm rounded-sm" href="/login">Log In</a>
        <a class="transition bg-green-700 text-green-300 hover:bg-green-600 p-2 px-4 self-center font-bold text-sm rounded-sm hidden sm:block " href="/join">Join</a>
    </div>
</div>