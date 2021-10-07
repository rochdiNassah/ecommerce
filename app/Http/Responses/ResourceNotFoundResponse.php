<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ResourceNotFoundResponse implements Responsable
{
    public function __construct(
        private $message
    )
    {

    }

    public function toResponse($request)
    {
        return back()->with([
            'status' => 'error',
            'message' => __($this->message),
            'reason' => 'Not Found'
        ]);
    }
}