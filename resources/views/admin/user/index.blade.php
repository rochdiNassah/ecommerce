@extends('layouts.main')
@section('title', 'Active members')

@section('content')
<div class="flex">
    @include('admin.sidebar')
    
    <div class="grid place-items-center w-full p-2 space-y-2">
        <form>
            <div class="flex space-x-2">
                <input class="self-center text-gray-600 dark:text-gray-200 dark:bg-gray-900 border border-gray rounded-md appearance-none h-8 px-2 text-xs leading-tight outline-none" type="text" value="{{ request('search') ?? null }}" name="search" placeholder="Search by name">

                <select class="self-center bg-white dark:bg-gray-800 border border-gray w-full p-2 font-bold text-xs text-gray-600 dark:text-gray-300 rounded-md" name="filter">
                    <option value="" @if ('all' === request('filter')) selected @endif>Filter by role</option>
                    @foreach (['admin', 'dispatcher', 'delivery_driver'] as $role)
                        <option value="{{ $role }}" @if ($role === request('filter')) selected @endif>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                    @endforeach
                </select>

                <button class="transition bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 p-2 px-4 text-xs font-bold rounded-md">Filter</button>
            </div>
        </form>
        <div class="w-full sm:w-4/5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($members as $member)
            <div class="bg-white dark:bg-gray-800 border border-gray rounded-sm relative space-y-2">
                <div class="grid place-items-center pt-2"><img class="object-contain rounded-full w-20 h-20" src="{{ asset($member->avatar_path) }}" onerror="this.src='{{ config('app.default_avatar_path') }}'" alt="Avatar"/></div>
                <div class="flex space-x-4">
                    <div class="flex-1 space-y-2 px-4">
                        <div class="grid place-items-center">
                            <div class="self-center px-2 rounded-sm text-{{ $member->status === 'pending' ? 'yellow' : 'green' }}-500 text-xs font-bold">{{ $member->status }}</div>
                        </div>
                        <div class="self-center px-2 rounded-sm w-40 text-gray-600 dark:text-gray-200 text-xs truncate">{{ $member->fullname }}</div>
                        <div class="self-center px-2 rounded-sm w-40 text-gray-600 dark:text-gray-200 text-xs">{{ $member->email }}</div>
                        <div class="self-center px-2 rounded-sm w-40 text-gray-600 dark:text-gray-200 text-xs">{{ $member->phone_number }}</div>

                        @php
                            $roleColor = $member->role === 'admin' ? 'red' : ($member->role === 'dispatcher' ? 'yellow' : 'green')
                        @endphp

                        <div class="self-center px-2 rounded-sm w-40 text-gray-600 dark:text-gray-200 text-xs">Role: <span class="text-{{ $roleColor }}-600">{{ $member->role }}</span></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 border-t border-gray p-2 space-x-2">
                    @if ('pending' === $member->status)
                    <a
                        class="text-center font-bold bg-green-100 hover:bg-green-200 text-green-600 dark:text-green-300 dark:bg-green-800 dark:hover:bg-green-900 transition text-xs py-1 px-2 rounded-sm"
                        href="{{ route('user.approve', $member->id) }}"
                    >Approve</a> 
                    @else
                    <a
                        class="text-center font-bold bg-blue-100 hover:bg-blue-200 text-blue-600 dark:text-blue-300 dark:bg-blue-800 dark:hover:bg-blue-900 transition text-xs py-1 px-2 rounded-sm"
                        href="{{ route('user.update-role-view', $member->id) }}"
                    >Edit Role</a> 
                    @endif

                    <a
                        class="text-center font-bold bg-red-100 hover:bg-red-200 text-red-600 dark:text-red-300 dark:bg-red-800 dark:hover:bg-red-900 transition text-xs py-1 px-2 rounded-sm"
                        href="{{ route('user.delete', $member->id) }}"
                    >Delete</a> 
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-4 mb-8 mt-4 grid place-items-center">
            {{ $members->appends(['filter' => $filter, 'search' => $search])->links() }}
        </div>
    </div>
</div>
@stop