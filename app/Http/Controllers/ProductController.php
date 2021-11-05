<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $validated = $request->safe()->only(['name', 'price', 'image']);

        if ($request->file('image')) {
            $validated['image_path'] = Storage::put('images/products', $validated['image']);
        }
        if ($product = CreateProduct::store($validated)) {
            cache()->forget('home');
            CreateProduct::succeed();
        } else {
            CreateProduct::failed();
        }
    
        return app(CreateProductResponse::class);
    }

    /**
     * Delete the given product.
     * 
     * @param  \App\Services\DeleteProduct  $service
     * @param  int  $id
     * @return \App\Interfaces\Responses\DeleteProductResponse
     */
    public function delete(DeleteProduct $service, $id): DeleteProductResponse
    {
        $product = Product::findOrFail($id);

        cache()->forget('home');

        DeleteProduct::delete($product);
        DeleteProduct::succeed();
        
        return app(DeleteProductResponse::class);
    }
}
