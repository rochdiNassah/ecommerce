@php
    $sidebarBody = '
        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-700" href="'.route('users').'">
            <li class="transition text-gray-200 text-lg">Active members</li>
        </a>

        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-700" href="'.route('user.pending').'">
            <li class="transition text-gray-200 text-lg">Pending members</li>
        </a>

        <a class="transition p-2 px-4 border-b border-gray hover:bg-gray-700" href="'.route('products').'">
            <li class="transition text-gray-200 text-lg">Products</li>
        </a>

        <a class="transition p-2 px-8 border-b border-gray hover:bg-gray-700" href="'.route('product.create-view').'">
            <li class="transition text-gray-200 text-md">Create New Product</li>
        </a>
    '
@endphp

@include('layouts.member-sidebar', ['body' => $sidebarBody])