<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\{Order, Member};

class DispatchOrderViewResponse implements Responsable
{
     /** @var int */
     private $order_id;
 
     /** @param  int  $product_id */
     public function __construct(int $order_id)
     {
         $this->order_id = $order_id;
     }

    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        $order = Order::findOrFail($this->order_id);
        $delivery_drivers = Member::where('role', 'delivery_driver')->where('status', 'active')->get();

        return view('order.dispatch', compact('order', 'delivery_drivers'));
    }
}