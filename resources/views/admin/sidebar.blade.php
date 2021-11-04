@php
    $sidebarBody = '
        <a class="transition py-3 px-4 border-b border-gray hover:bg-gray-100 dark:hover:bg-gray-700" href="'.route('members').'">
            <li class="transition font-bold text-gray-600 dark:text-gray-200 text-xs">Active members</li>
        </a>

        <a class="transition py-3 px-4 border-b border-gray hover:bg-gray-100 dark:hover:bg-gray-700" href="'.route('member.pending').'">
            <li class="transition font-bold text-gray-600 dark:text-gray-200 text-xs">Pending members</li>
        </a>

        <a class="transition py-3 px-4 border-b border-gray hover:bg-gray-100 dark:hover:bg-gray-700" href="'.route('products').'">
            <li class="transition font-bold text-gray-600 dark:text-gray-200 text-xs">Products</li>
        </a>

        <a class="transition py-3 px-4 border-b border-gray hover:bg-gray-100 dark:hover:bg-gray-700" href="'.route('product.create-view').'">
            <li class="transition font-bold text-gray-600 dark:text-gray-200 text-xs">Create New Product</li>
        </a>
    '
@endphp

@include('layouts.member-sidebar', ['body' => $sidebarBody])