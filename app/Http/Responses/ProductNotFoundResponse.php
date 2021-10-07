<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ProductNotFoundResponse implements Responsable
{
    public function toResponse($request)
    {
        return back()->with([
            'status' => 'error',
            'message' => __('product.missing'),
            'reason' => 'Not Found'
        ]);
    }
}