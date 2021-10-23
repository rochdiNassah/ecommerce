<?php declare(strict_types=1);

namespace App\Services;

class DeleteProduct extends Service
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

        $this->response = [
            'status' => 'success',
            'message' => __('product.deleted')
        ];
    }
}