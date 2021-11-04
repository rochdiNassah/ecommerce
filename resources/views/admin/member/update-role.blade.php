@extends('layouts.main')
@section('title', "Update {$member->fullname }'s role")

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="px-2 sm:px-0">
                <div class="border bg-white border-gray mx-auto w-full sm:w-400 px-8 py-4 rounded-sm my-20">
                    <form action="{{ route('member.update-role') }}" method="post">
                        @csrf
            
                        <h1 class="my-2 font-bold text-xl text-current-600 dark:text-current-400">Update {{ $member->fullname }}'s role</h1>

                        @if ($errors->any())
                            <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                                <strong>{{ $errors->all()[0] }}</strong>
                            </div>
                        @endif
            
                        <div class="my-3">
                            <input type="hidden" value="{{ $member->id }}" name="id">
                            <select class="dark:bg-gray-800 border border-gray w-full p-3 mb-6 font-bold text-sm text-gray-600 dark:text-gray-300 rounded-sm appearance-none focus:shadow-outline" name="role">
                                <option {{ $member->role === 'delivery_driver' ? 'selected' : null }} value="delivery_driver">Delivery Driver</option>
                                <option {{ $member->role === 'dispatcher' ? 'selected' : null }} value="dispatcher">Dispatcher</option>
                                <option {{ $member->role === 'admin' ? 'selected' : null }} value="admin">Admin</option>
                            </select>
            
                            <button class="rounded-sm mb-2 transition w-full bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-sm py-2 px-4 font-bold" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop