@extends('layouts.main')
@section('title', 'Create new products')

@php
$inputs = [
    ['type' => 'text', 'name' => 'name', 'label' => 'Name'],
    ['type' => 'text', 'name' => 'price', 'label' => 'Price']
];
@endphp

@section('content')
    <div class="flex">
        @include('admin.sidebar')

        <div class="grid place-items-center w-full">
            <div class="px-2 sm:px-0">
                <div class="border border-gray mx-auto xs:w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 base-form rounded-sm px-10 py-4 my-20">
                    <form action="{{ route('product.create') }}" method="post" enctype="multipart/form-data">
                        @csrf
            
                        <h1 class="my-2 font-bold text-lg text-{{ $mainColor }}-400">Create new product</h1>
            
                        @if ($errors->any())
                            <div class="rounded-sm mt-4 my-2 bg-red-200 text-red-800 text-sm p-3 relative" role="alert">
                                <strong>{{ $errors->all()[0] }}</strong>
                            </div>
                        @endif
            
                        <div class="my-3">
                            <div class="grid place-items-center my-4">
                                <img class="self-center w-40 h-40 bg-gray-200 rounded-sm" src="{{ asset('images/products/default.jpg') }}" id="image">
            
                                <label class="cursor-pointer mt-6">
                                    <span class="mt-2 leading-normal px-4 py-2 bg-{{ $mainColor }}-900 hover:bg-{{ $mainColor }}-800 transition text-{{ $mainColor }}-400 font-bold text-sm rounded-sm">Select Image</span>
                                    <input class="hidden" type="file" name="image" id="imageInput">
                                </label>
                            </div>

                            @foreach ($inputs as $input)
                            <label class="font-bold text-sm text-gray-300">{{ $input['label'] }}</label>
                            <input
                                class="bg-gray-light text-white border border-gray rounded-sm mb-6 appearance-none w-full p-3 leading-tight outline-none"
                                type="{{ $input['type'] }}"
                                name="{{ $input['name'] }}"
                                value="{{ old($input['name']) }}"
                                autocomplete="off"
                            >
                            @endforeach
                            
                            <button class="rounded-sm my-2 transition w-full text-{{ $mainColor }}-400 bg-{{ $mainColor }}-900 hover:bg-{{ $mainColor }}-800 text-sm py-2 px-4" type="submit">
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