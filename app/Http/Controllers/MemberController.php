<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\{ApproveMember, DeleteMember, EditMemberRole};
use App\Interfaces\Responses\{
    DeleteMemberResponse,
    ApproveMemberResponse,
    UpdateMemberRoleResponse
};

class MemberController extends Controller
{
    /**
     * Approve a pending member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\ApproveMember  $service
     * @param  int  $id
     * @return \App\Interfaces\Responses\ApproveMemberResponse
     */
    public function approve(Request $request, ApproveMember $service, int $id): ApproveMemberResponse
    {
        $member = User::findOrFail($id);

        'active' === $member->status
            ? $service->already()
            : $service->approve($member);

        return app(ApproveMemberResponse::class);
    }

    /**
     * Delete the given member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\DeleteMember  $service
     * @param  int  $id
     * @return \App\Interfaces\Responses\DeleteMemberResponse
     */
    public function delete(Request $request, DeleteMember $service, int $id): DeleteMemberResponse
    {
        $member = User::findOrFail($id);

        Auth::user()->can('affect', $member)
            ? ('pending' === $member->status
                ? $service->rejectMember($member)
                : $service->delete($member))
            : $service->unauthorized();

        return app(DeleteMemberResponse::class);
    }

    /**
     * Upgrade or downgrade the given user.
     * 
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @param  \App\Services\EditMemberRole  $service
     * @return \App\Interfaces\Responses\UpdateMemberRoleResponse
     */
    public function updateRole(UpdateRoleRequest $request, EditMemberRole $service): UpdateMemberRoleResponse
    {
        extract($request->safe()->only('id', 'role'));

        $member = User::findOrFail($id);
        $action = $service->action($member, $role);

        if (false === $action) {
            $service->already("This member is already {$role}.");
        } else {
            Auth::user()->can('affect', $member)
                ? $service->update($member, $role, $action)
                : $service->unauthorized();
        }
        
        return app(UpdateMemberRoleResponse::class);
    }
}
