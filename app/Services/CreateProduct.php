<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Interfaces\Responses\CreateProductResponse;
use Illuminate\Support\Facades\Storage;

class CreateProduct extends BaseService
{
    private $data;

    /** @return void */
    public function store(): void
    {
        if (false === $this->extract()) {
            return;
        }

        Product::create($this->data);

        $response = [
            'status' => 'success',
            'message' => __('product.created'),
            'redirect_to' => route('products')
        ];

        $this->createResponse(CreateProductResponse::class, $response);
    }

    /** @return bool */
    private function extract(): bool
    {
        $this->data = $this->request->safe()->except('image');

        if ($this->request->file('image')) {
            $this->file = $this->request->file('image');

            if (!$this->data['image_path'] = Storage::put('images/products', $this->file)) {
                $this->failed();

                return false;
            }
        }

        return true;
    }

    /**
     * Flash inputs to the session.
     * 
     * @return void
     */
    private function flashInputs(): void
    {
        $this->request->flashExcept('image');
    }

    /**
     * Product creation failed.
     * 
     * @param  string|null  $message
     * @return void
     */
    private function failed($message = null)
    {
        $response = [
            'status' => 'error',
            'message' => $message ?? __('global.failed')
        ];
        
        $this->createResponse(CreateProductResponse::class, $response);

        $this->flashInputs();
    }
}