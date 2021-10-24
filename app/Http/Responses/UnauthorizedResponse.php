<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;

class UnauthorizedResponse implements Responsable
{
    /** @var bool|string */
    private $redirect_to = false;

    public function __construct($redirect_to = false)
    {
        if ($redirect_to) {
            $this->redirect_to = $redirect_to;
        }
    }

    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        $response = [
            'status' => 'error',
            'message' => __('global.unauthorized'),
            'reason' => 'Unauthorized'
        ];

        if ($this->redirect_to) {
            return redirect($this->redirect_to)
                ->with($response);
        }

        return back()->with($response);
    }
}
