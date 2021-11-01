<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Product;

class HomeViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        $search = request('search') ?? null;
        $products = Product::where(function ($query) use ($search) {
            !$search ?: $query->where('name', 'like', '%'.$search.'%');
        })->paginate(12);
        $data = ['products' => $products, 'query' => $search];

        return view('home', $data);
    }
}