@extends('layouts.main')
@section('title', 'Users')

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="p-4 w-full sm:w-4/5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($users as $user)
                <div class="border border-gray rounded-sm p-4 relative">
                    <div class="flex space-x-4">
                        <div class="text-center grid place-items-center space-y-1">
                            <img class="h-12 w-12" src="{{ asset($user->avatar_path) }}" alt="Avatar"/>
                            <div class="rounded-sm text-{{ $user->status === 'pending' ? 'yellow' : 'green' }}-500 text-xs font-bold">{{ $user->status }}</div>
                        </div>
    
                        <div class="flex-1 space-y-2 py-1">
                            <div class="self-center px-2 rounded-sm w-40 text-gray-600 font-bold text-sm truncate">{{ $user->fullname }}</div>
                            <div class="self-center px-2 rounded-sm w-40 text-gray-500 font-bold text-xs truncate">{{ $user->phone_number }}</div>

                            @php
                                $roleColor = $user->role === 'admin' ? 'red' : ($user->role === 'dispatcher' ? 'yellow' : 'green')
                            @endphp

                            <div class="self-center px-2 py-1 absolute -top-1 text-center rounded-sm right-1 w-30 bg-{{ $roleColor }}-200 text-{{ $roleColor }}-700 font-bold text-xs">{{ $user->role }}</div>
                            
                            <div class="grid grid-cols-2">
                                @if ('pending' === $user->status)
                                <a
                                    class="text-center font-bold bg-green-200 hover:bg-green-300 transition text-green-700 text-xs py-1 px-2 rounded-sm mx-2"
                                    href="{{ route('user.approve', $user->id) }}"
                                >Approve</a> 
                                @else
                                <a
                                    class="text-center font-bold bg-blue-200 hover:bg-blue-300 transition text-blue-700 text-xs py-1 px-2 rounded-sm mx-2"
                                    href="{{ route('user.update-role-view', $user->id) }}"
                                >Edit Role</a> 
                                @endif

                                <a
                                    class="text-center font-bold bg-red-200 hover:bg-red-300 transition text-red-700 text-xs py-1 px-2 rounded-sm mx-2"
                                    href="{{ route('user.delete', $user->id) }}"
                                >Delete</a> 
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop