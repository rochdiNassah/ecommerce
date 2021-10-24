<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\{ApproveMember, DeleteMember, EditMemberRole};
use App\Interfaces\Responses\{DeleteMemberResponse, ApproveMemberResponse, UpdateMemberRoleResponse};
use App\Http\Responses\UnauthorizedResponse;

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
     * @return \App\Interfaces\Response\DeleteMemberResponse|\App\Http\Responses\UnauthorizedResponse
     */
    public function delete(Request $request, DeleteMember $service, int $id): DeleteMemberResponse|UnauthorizedResponse
    {
        $member = User::findOrFail($id);

        if (! Auth::user()->can('affect', $member)) {
            return app(UnauthorizedResponse::class);
        }
        
        'pending' === $member->status
            ? $service->rejectMember($member)
            : $service->delete($member);

        return app(DeleteMemberResponse::class);
    }

    /**
     * Upgrade or downgrade the given user.
     * 
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @param  \App\Services\EditMemberRole  $service
     * @return \App\Interfaces\Response\UpdateMemberRoleResponse|\App\Http\Responses\UnauthorizedResponse
     */
    public function updateRole(UpdateRoleRequest $request, EditMemberRole $service): UpdateMemberRoleResponse|UnauthorizedResponse
    {
        extract($request->safe()->only('id', 'role'));

        $member = User::findOrFail($id);
        $action = $service->action($member, $role);

        if (false === $action) {
            $service->already("This member is already {$role}.");
        } else {
            if (! Auth::user()->can('affect', $member)) {
                return app(UnauthorizedResponse::class, ['redirect_to' => route('users')]);
            }

            $service->update($member, $role, $action);
        }
        
        return app(UpdateMemberRoleResponse::class);
    }
}
