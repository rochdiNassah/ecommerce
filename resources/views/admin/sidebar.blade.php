<div class="bg-{{ $mainColor }}-200 hover:bg-{{ $mainColor }}-300 cursor-pointer lg:hidden transition w-12 px-3 py-2 fixed top-2 left-2 z-10 grid place-items-center space-y-1 rounded-sm" id="open-dashboard-sidebar">
    <div class="w-full h-1 bg-{{ $mainColor }}-700 rounded-sm"></div>
    <div class="w-full h-1 bg-{{ $mainColor }}-700 rounded-sm"></div>
    <div class="w-full h-1 bg-{{ $mainColor }}-700 rounded-sm"></div>
</div>

<div class="bg-white transition border-r border-gray h-screen lg:left-0 fixed lg:relative w-full sm:w-80 hidden lg:block z-10" id="dashboard-sidebar">
    <div class="transition bg-{{ $mainColor }}-200 hover:bg-{{ $mainColor }}-300 cursor-pointer lg:hidden transition w-12 px-3 py-2 fixed top-2 left-2 z-10 grid place-items-center space-y-1 rounded-sm" id="close-dashboard-sidebar">
        <div class="w-full h-1 bg-{{ $mainColor }}-700 rounded-sm transform rotate-45 translate-y-1"></div>
        <div class="w-full h-1 bg-{{ $mainColor }}-700 rounded-sm transform -rotate-45 -translate-y-1"></div>
    </div>

    <div class="border-b border-gray p-4 px-4 grid place-items-center">
        <img class="inline object-cover w-16 h-16 mb-2 rounded-full" src="{{ asset(Auth::user()->avatar_path) }}" alt="Avatar"/>
        <h1 class="text-lg text-center text-gray-600 font-bold">{{ strtoupper(Auth::user()->fullname) }}</h1>
    </div>

    <ul class="grid">
        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-100" href="{{ route('users') }}">
            <li class="transition text-gray-700 text-lg">Users</li>
        </a>

        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-100" href="{{ route('products') }}">
            <li class="transition text-gray-700 text-lg">Products</li>
        </a>

        <a class="transition p-2 px-8 border-b border-gray hover:bg-gray-100" href="{{ route('product.create-view') }}">
            <li class="transition text-gray-700 text-md">Create New Product</li>
        </a>

        @if ('dashboard' !== Route::current()->uri)
        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-100" href="{{ route('dashboard') }}">
            <li class="transition text-gray-700 text-lg">Dashboard</li>
        </a>
        @endif
    </ul>

    <div class="border-t border-gray bottom-0 left-0 p-4 px-8 absolute w-full">
        <a href="{{ route('logout') }}"><div class="w-full text-sm text-red-700 bg-red-200 hover:bg-red-300 transition rounded-sm p-2 text-center font-bold">Log Out</div></a>
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

    window.addEventListener('resize', function(event) {
        if (sidebar.classList.contains('closed')) {
            if (1024 < innerWidth) {
                openSidebar()
            }
        }
    })
</script>