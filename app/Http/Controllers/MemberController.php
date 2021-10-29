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
    public function __construct()
    {
        $callback = function () {
            return new UnauthorizedResponse(['redirect_to' => route('users')]);
        };

        app()->bind(UnauthorizedResponse::class, $callback, 1);
    }

    /**
     * Approve a pending member.
     * 
     * @param  int  $id
     * @return \App\Interfaces\Responses\ApproveMemberResponse
     */
    public function approve(int $id): ApproveMemberResponse
    {
        $member = User::findOrFail($id);

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
     * @return \App\Interfaces\Response\DeleteMemberResponse|\App\Http\Responses\UnauthorizedResponse
     */
    public function delete(int $id): DeleteMemberResponse|UnauthorizedResponse
    {
        $member = User::findOrFail($id);

        if (!Auth::user()->can('affect', $member)) {
            return app(UnauthorizedResponse::class);
        }
        
        'pending' === $member->status
            ? DeleteMember::rejectMember($member)
            : DeleteMember::delete($member);

        return app(DeleteMemberResponse::class);
    }

    /**
     * Upgrade or downgrade the given user.
     * 
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @return \App\Interfaces\Response\UpdateMemberRoleResponse|\App\Http\Responses\UnauthorizedResponse
     */
    public function updateRole(UpdateRoleRequest $request): UpdateMemberRoleResponse|UnauthorizedResponse
    {
        extract($request->safe()->only('id', 'role'));

        $member = User::findOrFail($id);
        $action = EditMemberRole::getAction($member, $role);

        if (false === $action) {
            EditMemberRole::already("This member is already {$role}.");
        } else {
            if (!Auth::user()->can('affect', $member)) {
                return app(unauthorizedResponse::class);
            }

            EditMemberRole::update($member, $role, $action);
        }
        
        return app(UpdateMemberRoleResponse::class);
    }
}
