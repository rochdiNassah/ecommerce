@extends('layouts.main')
@section('title', "Update {$member->fullname }'s role")

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="px-2 sm:px-0">
                <div class="bg-dark border border-gray mx-auto xs:w-full base-form rounded-sm px-10 py-4 my-20">
                    <form action="{{ route('user.update-role') }}" method="post">
                        @csrf
            
                        <h1 class="my-2 font-bold text-lg text-{{ $mainColor }}-400">Update {{ $member->fullname }}'s role</h1>
            
                        @if ($errors->any())
                            <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                                <strong>{{ $errors->all()[0] }}</strong>
                            </div>
                        @endif
            
                        <div class="my-3">
                            <input type="hidden" value="{{ $member->id }}" name="id">
                            <select class="bg-lightdark border border-gray w-full p-3 mb-6 font-bold text-sm text-gray-300 rounded-sm appearance-none focus:shadow-outline" name="role">
                                <option {{ $member->role === 'delivery_driver' ? 'selected' : null }} value="delivery_driver">Delivery Driver</option>
                                <option {{ $member->role === 'dispatcher' ? 'selected' : null }} value="dispatcher">Dispatcher</option>
                                <option {{ $member->role === 'admin' ? 'selected' : null }} value="admin">Admin</option>
                            </select>
            
                            <button class="rounded-sm my-2 transition w-full text-{{ $mainColor }}-300 bg-{{ $mainColor }}-800 hover:bg-{{ $mainColor }}-900 text-sm font-bold py-2 px-4" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop