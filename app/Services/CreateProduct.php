<?php

namespace App\Services;

use App\Models\Product;

class CreateProduct extends Service
{
    private $data;

    protected $fileDestination = 'images/products';
    
    protected $redirectTo = 'products';

    /**
     * Store the users data.
     * 
     * @return void
     */
    public function store()
    {
        if (false === $this->extract()) return;

        $products = Product::create($this->data);

        $this->response = [
            'status' => 'success',
            'message' => __('product.created')
        ];
    }

    private function extract()
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
     * Flash inputs into the session.
     * 
     * @return void
     */
    private function flashInputs()
    {
        $this->request->flashExcept('image');
    }

    private function failed($message = null)
    {
        $this->response = [
            'status' => 'error',
            'message' => $message ?? __('global.failed')
        ];

        $this->flashInputs();

        $this->redirectTo = false;
    }
}