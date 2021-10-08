<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ModelNotFoundResponse implements Responsable
{
    public function __construct(
        private $model
    ) {
        
    }

    public function toResponse($request)
    {
        return back()->with([
            'status' => 'error',
            'message' => __("{$this->model}.missing"),
            'reason' => 'Not Found'
        ]);
    }
}