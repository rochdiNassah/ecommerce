<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ModelNotFoundResponse implements Responsable
{
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
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
