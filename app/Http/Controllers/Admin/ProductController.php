<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Services\{CreateProduct, DeleteProduct};

class ProductController extends Controller
{
    /**
     * Create a new product.
     * 
     * @param  \App\Http\Requests\CreateProductRequest  $request
     * @return \App\Services\CreateProduct
     */
    public function create(CreateProductRequest $request): CreateProduct
    {
        $responsable = app(
            CreateProduct::class,
            ['request' => $request]
        );
        $responsable->store();
        return $responsable;
    }

    /**
     * Delete the given product.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\DeleteProduct  $responsable
     * @param  int  $id
     * @return \App\Services\DeleteProduct
     */
    public function delete(Request $request, DeleteProduct $responsable, $id): DeleteProduct
    {
        $product = Product::findOrFail($id);
        $responsable->delete($product);
        return $responsable;
    }
}
