<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Product;

class ProductsViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        $sort = request('sort') ?? null;
        $search = request('search') ?? null;
        $products = Product::where(function ($query) use ($search) {
                !$search ?: $query->where('name', 'like', sprintf('%%%s%%', $search));
            });
        
        $sort
            ? $products->orderBy('price', $sort === 'highest' ? 'desc' : 'asc')
            : $products->orderBy('id', 'asc');

        $products->with('orders')->withCount('orders');

        return view('admin.product.index', [
            'products' => $products->paginate(12),
            'sort' => $sort,
            'search' => $search
        ]);
    }
}