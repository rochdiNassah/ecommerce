<div class="bg-green-700 hover:bg-green-600 cursor-pointer lg:hidden transition w-12 px-3 py-2 fixed top-2 left-2 z-10 grid place-items-center space-y-1 rounded-sm" id="open-dashboard-sidebar">
    <div class="w-full h-1 bg-green-500 rounded-sm"></div>
    <div class="w-full h-1 bg-green-500 rounded-sm"></div>
    <div class="w-full h-1 bg-green-500 rounded-sm"></div>
</div>

<div class="transition border-r border-gray-500 h-screen bg-gray-800 lg:left-0 fixed lg:relative w-full sm:w-80 hidden lg:block z-10" id="dashboard-sidebar">
    <div class="transition bg-green-700 hover:bg-green-600 cursor-pointer lg:hidden transition w-12 px-3 py-2 fixed top-2 left-2 z-10 grid place-items-center space-y-1 rounded-sm" id="close-dashboard-sidebar">
        <div class="w-full h-1 bg-green-500 rounded-sm transform rotate-45 translate-y-1"></div>
        <div class="w-full h-1 bg-green-500 rounded-sm transform -rotate-45 -translate-y-1"></div>
    </div>

    <div class="border-b border-gray-500 p-4 px-8 grid place-items-center">
        <img class="inline object-cover w-16 h-16 mb-2 rounded-full" src="{{ asset('images/default-avatar.png') }}" alt="Avatar"/>
        <h1 class="text-lg text-center text-gray-200 font-bold">{{ strtoupper(Auth::user()->fullname) }}</h1>
    </div>

    <ul class="mt-10 ml-8">
        <li class="my-4">
            <a class="transition text-gray-300 hover:text-gray-100 text-2xl" href="/dashboard/users">Users</a>
        </li>

        <li class="my-4">
            <a class="transition text-gray-300 hover:text-gray-100 text-2xl" href="/dashboard/products">Products</a>
        </li>

        <li class="my-4">
            <a class="transition text-gray-300 hover:text-gray-100 text-2xl" href="/dashboard/account">My Account</a>
        </li>
    </ul>

    <div class="border-t border-gray-500 bottom-0 left-0 p-4 px-8 absolute w-full">
        <a href="/logout"><div class="w-full text-sm text-red-300 bg-red-500 hover:bg-red-600 transition rounded-sm p-2 text-center font-bold">Log Out</div></a>
    </div>
</div>

<script>
    var openSidebar = document.getElementById('open-dashboard-sidebar')
    var closeSidebar = document.getElementById('close-dashboard-sidebar')
    var sidebar = document.getElementById('dashboard-sidebar')

    openSidebar.addEventListener('click', function () {
        removeClass(sidebar, 'hidden')
        closeSidebar.style.left = '10px'
        sidebar.style.left = '0'
    })

    closeSidebar.addEventListener('click', function () {
        closeSidebar.style.left = '-800px'
        sidebar.style.left = '-800px'
    })
</script>