@extends('layouts.main')
@section('title', 'Pending members')

@section('content')
<div class="flex">
    @include('admin.sidebar')
    
    <div class="grid place-items-center w-full p-2">
        <form>
            <div class="self-center mb-4">
                <input class="w-40 sm:w-64 md:w-400 h-10 text-gray-200 bg-gray-900 border border-gray rounded-full appearance-none p-3 text-sm leading-tight outline-none" type="text" value="{{ request('search') ?? null }}" name="search" placeholder="Search by name">
            </div>
            <div class="self-center flex space-x-2 mb-4">
                <select class="self-center bg-lightdark border border-gray w-full p-3 font-bold text-xs text-gray-300 rounded-md" name="filter">
                    <option value="" @if ('all' === request('filter')) selected @endif>Filter by role</option>
                    @foreach (['admin', 'dispatcher', 'delivery_driver'] as $role)
                        <option value="{{ $role }}" @if ($role === request('filter')) selected @endif>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                    @endforeach
                </select>

                <button class="self-center transition bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-{{ $mainColor }}-300 p-2 px-4 text-md font-bold rounded-md">Filter</button>
            </div>
        </form>
        <div class="w-full sm:w-4/5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($members as $member)
            <div class="bg-dark border border-gray rounded-sm relative space-y-2">
                <div class="grid place-items-center pt-2"><img class="object-contain rounded-full w-20 h-20" src="{{ asset($member->avatar_path) }}" onerror="this.src='{{ config('app.default_avatar_path') }}'" alt="Avatar"/></div>
                <div class="flex space-x-4">
                    <div class="flex-1 space-y-2 px-4">
                        <div class="grid place-items-center">
                            <div class="self-center px-2 rounded-sm text-{{ $member->status === 'pending' ? 'yellow' : 'green' }}-500 text-xs font-bold">{{ $member->status }}</div>
                        </div>
                        <div class="self-center px-2 rounded-sm w-40 text-gray-200 text-xs truncate">{{ $member->fullname }}</div>
                        <div class="self-center px-2 rounded-sm w-40 text-gray-200 text-xs">{{ $member->email }}</div>
                        <div class="self-center px-2 rounded-sm w-40 text-gray-200 text-xs">{{ $member->phone_number }}</div>

                        @php
                            $roleColor = $member->role === 'admin' ? 'red' : ($member->role === 'dispatcher' ? 'yellow' : 'green')
                        @endphp

                        <div class="self-center px-2 py-1 absolute -top-1 text-center rounded-sm right-1 w-30 bg-{{ $roleColor }}-800 text-{{ $roleColor }}-300 font-bold text-xs">{{ $member->role }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 border-t border-gray p-2 space-x-2">
                    @if ('pending' === $member->status)
                    <a
                        class="text-center font-bold bg-green-800 hover:bg-green-900 transition text-green-300 text-xs py-1 px-2 rounded-sm"
                        href="{{ route('user.approve', $member->id) }}"
                    >Approve</a> 
                    @else
                    <a
                        class="text-center font-bold bg-blue-800 hover:bg-blue-900 transition text-blue-300 text-xs py-1 px-2 rounded-sm"
                        href="{{ route('user.update-role-view', $member->id) }}"
                    >Edit Role</a> 
                    @endif

                    <a
                        class="text-center font-bold bg-red-800 hover:bg-red-900 transition text-red-300 text-xs py-1 px-2 rounded-sm"
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