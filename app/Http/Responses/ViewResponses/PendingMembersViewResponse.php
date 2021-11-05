<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Member;

class PendingMembersViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        $filter = request('filter') ?? null;
        $search = request('search') ?? null;
        $members = Member::where(function ($query) use ($filter, $search) {
                !$filter ?: $query->where('role', $filter);
                !$search ?: $query->where('fullname', 'like', sprintf('%%%s%%', $search));
            })
            ->where('status', 'pending')
            ->orderBy('id', 'asc')
            ->paginate(12);
        
        return view('admin.member.index', compact('members', 'search', 'filter'));
    }
}