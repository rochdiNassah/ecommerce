<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Services\{CreateProduct, DeleteProduct};
use App\Interfaces\Responses\CreateProductResponse;
use App\Interfaces\Responses\DeleteProductResponse;

class ProductController extends Controller
{
    /**
     * Create a new product.
     * 
     * @param  \App\Http\Requests\CreateProductRequest  $request
     * @return \App\Interfaces\Responses\CreateProductResponse
     */
    public function create(CreateProductRequest $request): CreateProductResponse
    {
        $service = app(
            CreateProduct::class,
            ['request' => $request]
        );

        $service->store();

        return app(CreateProductResponse::class);
    }

    /**
     * Delete the given product.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\DeleteProduct  $service
     * @param  int  $id
     * @return \App\Interfaces\Responses\DeleteProductResponse
     */
    public function delete(Request $request, DeleteProduct $service, $id): DeleteProductResponse
    {
        $product = Product::findOrFail($id);

        $service->delete($product);
        
        return app(DeleteProductResponse::class);
    }
}
