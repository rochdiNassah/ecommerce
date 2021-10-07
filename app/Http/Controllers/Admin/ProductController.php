<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Responses\ProductNotFoundResponse;
use App\Services\{CreateProduct, DeleteProduct};

class ProductController extends Controller
{
    /**
     * Create a new product.
     * 
     * @param  \App\Http\Requests\CreateProductRequest  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function create(CreateProductRequest $request)
    {
        $responsable = app(CreateProduct::class, ['request' => $request]);

        $responsable->store();

        return $responsable;
    }

    /**
     * Delete the given product.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\DeleteProduct  $responsable
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function delete(Request $request, DeleteProduct $responsable, $id)
    {
        if (! $product = Product::find($id))
        return app(ProductNotFoundResponse::class);
        
        $responsable->delete($product);

        return $responsable;
    }
}
