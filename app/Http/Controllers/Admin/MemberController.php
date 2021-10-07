<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\{UserRejected};
use App\Http\Requests\UpdateRoleRequest;
use App\Services\{ApproveUser, DeleteUser, EditUserRole};

class MemberController extends Controller
{
    /**
     * Approve a pending member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, ApproveUser $responsable, int $id)
    {
        try {
            $user = User::findOrFail($id);

            'active' === $user->status
                ? $responsable->already(__('user.active'))
                : $responsable->approve($user);
        } catch (ModelNotFoundException $e) {
            $responsable->notFound();
        }

        return $responsable;
    }

    /**
     * Delete the given member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, DeleteUser $responsable, int $id)
    {
        try {
            $user = User::findOrFail($id);

            Auth::user()->can('delete', $user)
                ? $responsable->delete($user)
                : $responsable->unauthorized();
        } catch (ModelNotFoundException) {
            $responsable->notFound();
        }

        return $responsable;
    }

    /**
     * Upgrade or downgrade the given user.
     * 
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRole(UpdateRoleRequest $request, EditUserRole $responsable)
    {
        extract($request->safe()->only('id', 'role'));

        try {
            $user = User::findOrFail($id);
            $action = $responsable->action($user, $role);

            if (false === $action) {
                $responsable->already("This user is alread {$role}.");
            } else {
                Auth::user()->can('updateRole', $user)
                ? $responsable->update($user, $role, $action)
                : $responsable->unauthorized();
            }
        } catch (ModelNotFoundException) {
            $responsable->notFound();
        }

        return $responsable;
    }
}
