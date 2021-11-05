@extends('layouts.main')
@section('title', 'Create new products')

@php
$inputs = [
    ['type' => 'text', 'name' => 'name', 'label' => 'Name'],
    ['type' => 'number', 'name' => 'price', 'label' => 'Price']
];
@endphp

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="px-2 sm:px-0">
                <div class="border bg-white border-gray mx-auto w-full sm:w-400 px-8 py-4 rounded-sm my-20">
                    <form action="{{ route('product.create') }}" method="post" enctype="multipart/form-data">
                        @csrf
            
                        <h1 class="my-2 font-bold text-xl text-current-600 dark:text-current-400">Create new product</h1>
            
                        @if ($errors->any())
                            <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                                <strong>{{ $errors->all()[0] }}</strong>
                            </div>
                        @endif
            
                        <div class="my-3">
                            <div class="grid place-items-center my-4">
                                <img class="self-center w-32 h-32 rounded-sm object-contain bg-transparent-200" src="{{ asset('images/products/default.png') }}" id="image">
            
                                <label class="cursor-pointer mt-6">
                                    <span class="mt-2 leading-normal px-4 py-2 bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 transition font-bold text-sm rounded-sm">Select Image</span>
                                    <input class="hidden" type="file" name="image" id="imageInput">
                                </label>
                            </div>

                            @foreach ($inputs as $input)
                            <label class="text-xs text-gray-500 dark:text-gray-300">{{ $input['label'] }}</label>
                            <input
                                class="text-gray-600 dark:text-gray-200 dark:bg-lightdark border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                                type="{{ $input['type'] }}"
                                name="{{ $input['name'] }}"
                                value="{{ $input['value'] ?? null }}"
                                autocomplete="off"
                            >
                            @endforeach
                            
                            <button class="rounded-sm my-2 transition w-full bg-current-100 hover:bg-current-200 text-current-600 dark:text-current-300 dark:bg-current-800 dark:hover:bg-current-900 text-sm font-bold py-2 px-4" type="submit">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    var avatarInput = document.getElementById('imageInput')
    var avatarImg = document.getElementById('image')

    avatarInput.addEventListener('change', function (e) {
        avatarImg.src = URL.createObjectURL(e.target.files[0])
    })
</script>
@stop