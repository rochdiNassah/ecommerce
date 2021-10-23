<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;

class ModelNotFoundResponse implements Responsable
{
    /** @var string */
    private $model;
    
    /**
     * @param  string  $model
     * @return void
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return back()->with([
            'status' => 'error',
            'message' => __("{$this->model}.missing"),
            'reason' => 'Not Found'
        ]);
    }
}
