<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Services\CreateProduct;

class ProductController extends Controller
{
    /**
     * Create a new product.
     * 
     * @param  \App\Http\Requests\CreateProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateProductRequest $request)
    {
        $responsable = app(CreateProduct::class, ['request' => $request]);

        $responsable->store();

        return $responsable;
    }
}
