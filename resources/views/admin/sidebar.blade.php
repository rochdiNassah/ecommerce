@php
    $sidebarBody = '
        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-100" href="'.route('users').'">
            <li class="transition text-gray-700 text-lg">Users</li>
        </a>

        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-100" href="'.route('products').'">
            <li class="transition text-gray-700 text-lg">Products</li>
        </a>

        <a class="transition p-2 px-8 border-b border-gray hover:bg-gray-100" href="'.route('product.create-view').'">
            <li class="transition text-gray-700 text-md">Create New Product</li>
        </a>
    '
@endphp

@include('layouts.member-sidebar', ['body' => $sidebarBody])