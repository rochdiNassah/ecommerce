<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Product;

class CreateOrderViewResponse implements Responsable
{
     /** @var int */
     private $product_id;
 
     /** @param  int  $product_id */
     public function __construct(int $product_id)
     {
         $this->product_id = $product_id;
     }

    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        return view('order.create', [
            'product' => Product::findOrFail($this->product_id)
        ]);
    }
}