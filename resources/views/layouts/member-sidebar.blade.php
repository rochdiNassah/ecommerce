<div 
    class="fixed top-2 left-2 z-10 grid place-items-center space-y-1 bg-current-200 hover:bg-current-300 dark:bg-current-700 dark:hover:bg-current-800 cursor-pointer transition w-12 px-3 py-2 rounded-sm"
    id="open-dashboard-sidebar"
>
    @for($i=0;$i<3;$i++)<div class="w-full h-1 bg-current-600 dark:bg-current-300 rounded-sm"></div>@endfor
</div>

<div
    class="fixed -left-800 z-10 w-full h-screen sm:w-300 bg-white dark:bg-gray-600 transition border-r border-gray"
    id="dashboard-sidebar"
>
    <div
        class="-left-800 transition bg-current-200 hover:bg-current-300 cursor-pointer transition w-12 px-3 py-2 fixed top-2 left-2 z-10 grid place-items-center space-y-1 rounded-sm"
        id="close-dashboard-sidebar"
    >
        <div class="w-full h-1 bg-current-600 dark:bg-current-300 rounded-sm transform rotate-45 translate-y-1"></div>
        <div class="w-full h-1 bg-current-600 dark:bg-current-300 rounded-sm transform -rotate-45 -translate-y-1"></div>
    </div>

    <div class="border-b border-gray p-4 px-4 grid place-items-center">
        <img class="object-contain rounded-full w-20 h-20 mb-2" src="{{ asset(Auth::user()->avatar_path) }}" onerror="this.src='{{ asset(config('app.default_avatar_path')) }}'" alt="Avatar"/>
        <h1 class="text-lg text-center text-gray-600 dark:text-gray-200 font-bold">{{ strtoupper(Auth::user()->fullname) }}</h1>
    </div>

    <ul class="grid">
        {!! $body !!}
        @if ('dashboard' !== Route::current()->uri)
        <a class="transition py-3 px-4 border-b border-gray hover:bg-gray-100 dark:hover:bg-gray-700" href="{{ route('dashboard') }}">
            <li class="transition font-bold text-gray-600 dark:text-gray-200 text-sm">Dashboard</li>
        </a>
        @endif
    </ul>

    <div class="border-t border-gray bottom-0 left-0 p-4 px-8 absolute w-full">
        <a href="{{ route('logout') }}"><div class="w-full text-sm bg-red-100 hover:bg-red-200 text-red-600 dark:text-red-300 dark:bg-red-800 dark:hover:bg-red-900 transition rounded-sm p-2 text-center font-bold">Log Out</div></a>
    </div>
</div>

<script>
    var openSidebarButton = document.getElementById('open-dashboard-sidebar')
    var closeSidebarButton = document.getElementById('close-dashboard-sidebar')
    var sidebar = document.getElementById('dashboard-sidebar')

    openSidebarButton.addEventListener('click', function () {
        openSidebar()
    })

    closeSidebarButton.addEventListener('click', function () {
        closeSidebar()
    })

    function openSidebar()
    {
        removeClass(sidebar, 'hidden')
        closeSidebarButton.style.left = '10px'
        sidebar.style.left = '0'
    }

    function closeSidebar()
    {
        closeSidebarButton.style.left = '-800px'
        sidebar.style.left = '-800px'
        addClass(sidebar, 'closed')
    }
</script>