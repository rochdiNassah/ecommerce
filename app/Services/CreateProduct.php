<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

class CreateProduct extends Service
{
    private $data;
    protected $fileDestination = 'images/products';
    protected $redirectTo = 'products';

    /** @return void */
    public function store(): void
    {
        if (false === $this->extract()) {
            return;
        }

        Product::create($this->data);

        $this->response = [
            'status' => 'success',
            'message' => __('product.created')
        ];
    }

    /** @return bool */
    private function extract(): bool
    {
        $this->data = $this->request->safe()->except('image');

        if ($this->request->file('image')) {
            $this->file = $this->request->file('image');

            if (!$this->data['image_path'] = $this->storeFile()) {
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
        $this->response = [
            'status' => 'error',
            'message' => $message ?? __('global.failed')
        ];
        $this->redirectTo = false;

        $this->flashInputs();
    }
}