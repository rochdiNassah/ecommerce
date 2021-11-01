<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Order;

class TrackOrderViewResponse implements Responsable
{
    /** @var string */
    private $token;

    /** @param  string  $token */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        $order = Order::where('token', $this->token)->firstOrFail();

        return view('order.track', ['order' => $order]);
    }
}