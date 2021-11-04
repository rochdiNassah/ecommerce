<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use App\Interfaces\Responses\LoginResponse;
use App\Interfaces\Responses\LogoutResponse;
use App\Interfaces\Responses\ApproveMemberResponse;
use App\Interfaces\Responses\DeleteMemberResponse;
use App\Interfaces\Responses\UpdateMemberRoleResponse;
use App\Interfaces\Responses\PlaceOrderResponse;
use App\Interfaces\Responses\CreateProductResponse;
use App\Interfaces\Responses\DeleteProductResponse;
use App\Interfaces\Responses\RequestJoinResponse;
use App\Interfaces\Responses\RejectOrderResponse;
use App\Interfaces\Responses\DispatchOrderResponse;
use App\Interfaces\Responses\UpdateOrderStatusResponse;
use App\Interfaces\Responses\ForgotPasswordResponse;
use App\Interfaces\Responses\ResetPasswordResponse;
use App\Interfaces\Responses\RequestMyOrdersResponse;

class ServiceResponse implements
    LoginResponse,
    LogoutResponse,
    ApproveMemberResponse,
    DeleteMemberResponse,
    UpdateMemberRoleResponse,
    PlaceOrderResponse,
    CreateProductResponse,
    DeleteProductResponse,
    RequestJoinResponse,
    RejectOrderResponse,
    DispatchOrderResponse,
    UpdateOrderStatusResponse,
    ForgotPasswordResponse,
    ResetPasswordResponse,
    RequestMyOrdersResponse
{
    /** @var array */
    private $response;

    /** @var bool|string */
    private $redirect_to = false;

    /** @param  array  $response */
    public function __construct(array $response)
    {
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
