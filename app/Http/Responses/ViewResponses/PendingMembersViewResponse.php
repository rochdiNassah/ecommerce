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
        $members = Member::orderBy('id')
            ->where(function ($query) use ($filter, $search) {
                if ($filter) {
                    $query->where('role', $filter);
                }
                if ($search) {
                    $query->where('fullname', 'like', '%'.$search.'%');
                }
            })
            ->where('status', 'pending')
            ->paginate(12);
        
        return view('admin.member.pending', [
            'members' => $members,
            'filter' => $filter,
            'search' => $search
        ]);
    }
}