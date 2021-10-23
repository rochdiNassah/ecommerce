<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use App\Interfaces\Responses\{
    LoginResponse,
    LogoutResponse
};

class ServiceResponse implements
    LoginResponse,
    LogoutResponse
{
    /** @var array */
    private $response;

    /** @var bool|string */
    private $redirect_to = false;

    /**
     * @param  string  $status
     * @return void
     */
    public function __construct(array $response) {
        $this->response = $response;

        if (array_key_exists('redirect_to', $response)) {
            $this->redirect_to = $response['redirect_to'];
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
        if ($this->redirect_to) {
            return redirect($this->redirect_to)
                ->with($this->response);
        }

        return back()->with($this->response);
    }
}