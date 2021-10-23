<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Storage;

class Service implements Responsable
{
    protected $request;
    protected $response = false;
    protected $redirectTo = false;
    protected $file;
    protected $fileDestination;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    /**
     * The attempted action is not authorized.
     * 
     * @param  string|null  $message
     * @return void
     */
    public function unauthorized($message = null): void
    {
        $this->response = [
            'status' => 'error',
            'message' => $message ?? __('global.unauthorized'),
            'reason' => 'Unauthorized'
        ];
    }

    /**
     * The given resource is already under a state.
     * 
     * @param  string  $message
     * @return void
     */
    public function already($message): void
    {
        $this->response = [
            'status' => 'warning',
            'message' => $message,
            'reason' => 'Already'
        ];
        $this->redirectTo = false;
    }

    protected function storeFile()
    {
        return Storage::putFile($this->fileDestination, $this->file);
    }

    public function toResponse($request)
    {
        return $this->redirectTo === false
            ? back()->with($this->response)
            : redirect(route($this->redirectTo))->with($this->response);
    }
}