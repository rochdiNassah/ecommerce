@extends('layouts.main')
@section('title', "Update {$user->fullname }'s role")

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="px-2 sm:px-0">
                <div class="border border-gray mx-auto xs:w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 base-form rounded-sm px-10 py-4 my-20">
                    <form action="{{ route('user.update-role') }}" method="post">
                        @csrf
            
                        <h1 class="my-2 font-bold text-lg text-{{ $mainColor }}-400">Update {{ $user->fullname }}'s role</h1>
            
                        @if ($errors->any())
                            <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                                <strong>{{ $errors->all()[0] }}</strong>
                            </div>
                        @endif
            
                        <div class="my-3">
                            <input type="hidden" value="{{ $user->id }}" name="id">
                            <select class="border border-gray w-full p-3 mb-6 font-bold text-sm text-gray-600 rounded-sm appearance-none focus:shadow-outline" name="role">
                                <option {{ $user->role === 'delivery_driver' ? 'selected' : null }} value="delivery_driver">Delivery Driver</option>
                                <option {{ $user->role === 'dispatcher' ? 'selected' : null }} value="dispatcher">Dispatcher</option>
                                <option {{ $user->role === 'admin' ? 'selected' : null }} value="admin">Admin</option>
                            </select>
            
                            <button class="rounded-sm my-2 transition w-full text-{{ $mainColor }}-600 bg-{{ $mainColor }}-100 hover:bg-{{ $mainColor }}-200 text-sm font-bold py-2 px-4" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop