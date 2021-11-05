<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Pagination\LengthAwarePaginator;
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
        $page = request('page') ?? 1;
        $products = cache()->rememberForever('home', function () {
            return Product::get();
        });

        if ($search) {
            $products = $products->filter(function ($item) use ($search) {
                return preg_match("#{$search}#i", $item->name);
            });
        }

        $is_paginating = $products->count() > 12 ? true : false;
        $products = new LengthAwarePaginator($products->forPage($page, 12), 12, $page);
    
        return view('home', compact('products', 'search', 'is_paginating'));
    }
}
