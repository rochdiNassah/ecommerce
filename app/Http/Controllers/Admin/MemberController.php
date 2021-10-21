<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\{ApproveUser, DeleteUser, EditUserRole};

class MemberController extends Controller
{
    /**
     * Approve a pending member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\ApproveUser  $responsable
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function approve(Request $request, ApproveUser $responsable, int $id): Responsable
    {
        $user = User::findOrFail($id);

        'active' === $user->status
            ? $responsable->already(__('user.active'))
            : $responsable->approve($user);

        return $responsable;
    }

    /**
     * Delete the given member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\DeleteUser  $responsable
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function delete(Request $request, DeleteUser $responsable, int $id): Responsable
    {
        $user = User::findOrFail($id);

        Auth::user()->can('affect', $user)
            ? ('pending' === $user->status
                ? $responsable->rejectUser($user)
                : $responsable->delete($user))
            : $responsable->unauthorized();

        return $responsable;
    }

    /**
     * Upgrade or downgrade the given user.
     * 
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @param  \App\Services\EditUserRole  $responsable
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function updateRole(UpdateRoleRequest $request, EditUserRole $responsable): Responsable
    {
        extract($request->safe()->only('id', 'role'));

        $user = User::findOrFail($id);

        $action = $responsable->action($user, $role);

        if (false === $action) {
            $responsable->already("This user is already {$role}.");
        } else {
            Auth::user()->can('affect', $user)
                ? $responsable->update($user, $role, $action)
                : $responsable->unauthorized();
        }

        return $responsable;
    }
}
