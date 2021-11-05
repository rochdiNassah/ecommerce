<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Order;

class MyOrdersViewResponse implements Responsable
{
    /** @var string */
    private $email;
    
    /** @var string */
    private $token;

    /**
     * @param  string  $email
     * @param  string  $token
     */
    public function __construct(string $email, string $token)
    {
        $this->email = $email;
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
        $email = $this->email;
        $token = $this->token;
        $filter = request('filter') ?? null;

        Order::where('customer->email', $email)->where('token', $token)->firstOrFail();
        
        $orders = Order::where('customer->email', $email)
            ->where(function ($query) use ($filter) {
                if ('canceled' === $filter || 'rejected' === $filter) {
                    $query->where('status', $filter);
                } elseif (null === $filter) {
                    $query->where('status', 'REGEXP', '^(?!rejected|canceled).*$');
                } else {
                    $query->where('status', $filter);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('order.my-orders', compact('orders', 'filter'));
    }
}