<?php declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Responses\ModelNotFoundResponse;
use App\Http\Responses\UnauthorizedResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return app(UnauthorizedResponse::class);
        }

        if ($e instanceof ModelNotFoundException) {
            preg_match('#(?!\\\)(\w*)$#', $e->getModel(), $match);

            $model = strtolower($match[0]);
            
            return app(ModelNotFoundResponse::class, ['model' => $model]);
        }

        return parent::render($request, $e);
    }
}
