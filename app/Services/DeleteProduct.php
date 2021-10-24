<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Responses\DeleteProductResponse;

class DeleteProduct extends BaseService
{
    /**
     * Delete the given user.
     * 
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function delete($product): void
    {
        $product->delete();

        $response = [
            'status' => 'success',
            'message' => __('product.deleted')
        ];

        $this->createResponse(DeleteProductResponse::class, $response);
    }
}