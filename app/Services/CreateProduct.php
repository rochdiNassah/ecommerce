<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Interfaces\Responses\CreateProductResponse;

class CreateProduct extends BaseService
{
    /**
     * @param  array  $validated
     * @return mixed
     */
    public static function store($validated): mixed
    {
        return Product::create($validated);
    }

    /**
     * Product created successfully.
     * 
     * @return void
     */
    public static function succeed()
    {
        $response = [
            'status' => 'success',
            'message' => __('product.created'),
            'redirect_to' => route('products')
        ];

        self::createResponse(CreateProductResponse::class, $response);
    }

    /**
     * Product creation failed.
     * 
     * @param  string|null  $message
     * @return void
     */
    public static function failed($message = null)
    {
        $response = [
            'status' => 'error',
            'message' => $message ?? __('global.failed')
        ];
        
        self::createResponse(CreateProductResponse::class, $response);
        self::flashInputs();
    }
}