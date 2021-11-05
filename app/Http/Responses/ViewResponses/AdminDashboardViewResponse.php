<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\{Member, Product};

class AdminDashboardViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\View
     */
    public function toResponse($request): View
    {
        $membersCount = Member::all()->count();
        $productsCount = Product::all()->count();

        return view('admin.dashboard', compact('membersCount', 'productsCount'));
    }
}