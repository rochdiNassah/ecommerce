<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;

class UnauthorizedResponse implements Responsable
{
    /** @var bool|string */
    private $redirect_to;

    /** @var array */
    private $response;

    public function __construct($response = null)
    {
        $this->response['status'] = $response['status'] ?? 'error';
        $this->response['message'] = $response['message'] ?? __('global.unauthorized');
        $this->response['reason'] = $response['reason'] ?? 'Unauthorized';
        $this->redirect_to = $response['redirect_to'] ?? false;
    }

    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        if ($this->redirect_to) {
            return redirect($this->redirect_to)
                ->with($this->response);
        }

        return back()->with($this->response);
    }
}
