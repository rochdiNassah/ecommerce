<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Responses\DeleteProductResponse;

class DeleteProduct extends BaseService
{
    /**
     * Delete the given product.
     * 
     * @param  \App\Models\Product  $product
     * @return void
     */
    public static function delete($product): void
    {
        $product->delete();
    }

    /** @return void */
    public static function succeed(): void
    {
        $response = [
            'status' => 'success',
            'message' => __('product.deleted')
        ];

        self::createResponse(DeleteProductResponse::class, $response);
    }

    /** @return void */
    public static function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];

        self::createResponse(DeleteProductResponse::class, $response);
    }
}