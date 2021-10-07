<?php

namespace App\Services;

abstract class UserService
{
    protected $response = [];
    protected $redirectTo = false;

    public function __construct(
        public $user = null
    ) {

    }

    /**
     * The given user is not found.
     * 
     * @return void
     */
    public function notFound()
    {
        $this->response = [
            'status' => 'error',
            'message' => __('user.missing'),
            'reason' => 'Not Found'
        ];
    }

    /**
     * The attempted action is not authorized.
     * 
     * @param  string|null  $message
     * @return void
     */
    public function unauthorized($message = null)
    {
        $this->response = [
            'status' => 'error',
            'message' => $message ?? __('global.unauthorized'),
            'reason' => 'Unauthorized'
        ];
    }

    /**
     * The given user is already under a state.
     * 
     * @param  string  $message
     * @return void
     */
    public function already($message)
    {
        $this->response = [
            'status' => 'warning',
            'message' => $message,
            'reason' => 'Already'
        ];
    }

    public function toResponse($request)
    {
        return $this->redirectTo === false
            ? back()->with($this->response)
            : redirect(route($this->redirectTo))->with($this->response);
    }
}