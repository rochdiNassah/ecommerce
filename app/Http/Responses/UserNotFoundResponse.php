<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class UserNotFoundResponse implements Responsable
{
    public function toResponse($request)
    {
        return back()->with([
            'status' => 'error',
            'message' => __('user.missing'),
            'reason' => 'Not Found'
        ]);
    }
}