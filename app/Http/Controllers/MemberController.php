<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\{ApproveMember, DeleteMember, UpdateMemberRole};
use App\Interfaces\Responses\{DeleteMemberResponse, ApproveMemberResponse, UpdateMemberRoleResponse};
use App\Http\Responses\UnauthorizedResponse;

class MemberController extends Controller
{
    public function __construct()
    {
        app()->singleton(UnauthorizedResponse::class, function () {
            return new UnauthorizedResponse(['redirect_to' => route('members')]);
        });
    }

    /**
     * Approve a pending member.
     * 
     * @param  int  $id
     * @return \App\Interfaces\Responses\ApproveMemberResponse
     */
    public function approve(int $id): ApproveMemberResponse
    {
        $member = Member::findOrFail($id);

        if ('pending' !== $member->status) {
            ApproveMember::already();
        } else {
            ApproveMember::approve($member);
            ApproveMember::notify($member);
            ApproveMember::succeed();
        }
        
        return app(ApproveMemberResponse::class);
    }

    /**
     * Delete the given member.
     * 
     * @param  int  $id
     * @return \App\Interfaces\Responses\DeleteMemberResponse|\App\Http\Responses\UnauthorizedResponse
     */
    public function delete(int $id): DeleteMemberResponse|UnauthorizedResponse
    {
        $member = Member::findOrFail($id);

        if (!Auth::user()->can('affect', $member)) {
            return app(UnauthorizedResponse::class);
        }
        
        'pending' === $member->status
            ? DeleteMember::rejectMember($member)
            : DeleteMember::delete($member);

        return app(DeleteMemberResponse::class);
    }

    /**
     * Upgrade or downgrade the given member.
     * 
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @return \App\Interfaces\Responses\UpdateMemberRoleResponse|\App\Http\Responses\UnauthorizedResponse
     */
    public function updateRole(UpdateRoleRequest $request): UpdateMemberRoleResponse|UnauthorizedResponse
    {
        extract($request->safe()->only('id', 'role'));

        $member = Member::findOrFail($id);
        $action = UpdateMemberRole::getAction($member->role, $role);

        if ($role === $member->role) {
            UpdateMemberRole::already("This member is already {$role}.");
        } else {
            if (!Auth::user()->can('affect', $member)) {
                return app(UnauthorizedResponse::class);
            }

            UpdateMemberRole::update($member, $role, $action);
        }
        
        return app(UpdateMemberRoleResponse::class);
    }
}
