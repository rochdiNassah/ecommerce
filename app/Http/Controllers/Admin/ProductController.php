<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\{View, Storage};
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;

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
        $data = $request->safe()->only('name', 'price', 'description');

        if ($request->file('image')) {
            if (! $data['image_path'] = Storage::putFile("images/images", $request->file('image'))) {
                return back()
                    ->with([
                        'status' => 'error',
                        'message' => __('global.failed')
                    ]);
            }
        }

        try {
            Product::create($data);

            return redirect(route('products'))
                ->with([
                    'status' => 'success',
                    'message' => __('product.created')
                ]);
        } catch (QueryException $e) {
            return back()
                ->with([
                    'status' => 'error',
                    'message' => __('global.failed')
                ]);
        }
    }
}
