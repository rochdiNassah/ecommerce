<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\{View, Auth};
use App\Models\User;
use App\Notifications\{UserApproved, UserRejected};
use App\Http\Requests\UpdateRoleRequest;

class MemberController extends Controller
{
    /**
     * Display all members.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        return View::make('admin.users', ['users' => User::orderBy('status')->get()]);
    }

    /**
     * Approve a pending member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($id);

            if ('active' !== $user->status) {
                $user->status = 'active';
                $user->save();
                $user->notify((new UserApproved())->delay(now()->addMinutes(4)));
                
                $response = ['status' => 'success', 'message' => __('user.approved')];
            } else {
                $response = [
                    'status' => 'warning',
                    'message' => __('user.active'),
                    'reason' => 'Already'
                ];
            }
    
            return back()->with($response);
        } catch (ModelNotFoundException $e) {
            return back()->with([
                'status' => 'error',
                'message' => __('user.missing'),
                'reason' => 'Not Found'
            ]);
        }
    }

    /**
     * Delete the given member.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($id);

            if ('admin' === $user->role && !Auth::user()->is_super_admin || $user->is_super_admin) {
                $response = [
                    'status' => 'error',
                    'message' => __('global.unauthorized'),
                    'reason' => 'Unauthorized'
                ];
            } else {
                if ('pending' === $user->status) {
                    $user->notify((new UserRejected())->delay(now()->addMinutes(4)));
                }

                $user->delete();

                $response = ['status' => 'success', 'message' => __('user.deleted')];
            }

            return back()->with($response);
        } catch (ModelNotFoundException) {
            return back()->with([
                'status' => 'error',
                'message' => __('user.missing'),
                'reason' => 'Not Found'
            ]);
        }
    }

    /**
     * Display the edit role screen.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRoleScreen(int $id)
    {
        return View::make('admin.user.update-role', ['user' => User::findOrFail($id)]);
    }

    /**
     * Upgrade or downgrade the given user.
     * 
     * @param  \App\Http\Requests\UpdateRoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRole(UpdateRoleRequest $request)
    {
        extract($request->safe()->only('id', 'role'));

        $roles = ['admin' => 999, 'dispatcher' => 666, 'delivery_driver' => 333];

        try {
            $user = User::findOrFail($id);
            $isUpgrade = $roles[$role] > $roles[$user->role];

            if ($role === $user->role) {
                $response = [
                    'status' => 'warning',
                    'message' => "This user is already {$role}.",
                    'reason' => 'Already'
                ];
            } else {
                if ('admin' === $user->role && !Auth::user()->is_super_admin || $user->is_super_admin) {
                    $response = [
                        'status' => 'error',
                        'message' => __('global.unauthorized'),
                        'reason' => 'Unauthorized'
                    ];
                } else {
                    $user->role = $role;
                    $user->save();
                    $is = $isUpgrade ? 'upgraded' : 'downgraded';

                    $response = [
                        'status' => 'success',
                        'message' => __("user.{$is}"),
                        'reason' => ucfirst($is)
                    ];
                }
            }

            return redirect(route('users'))->with($response);
        } catch (ModelNotFoundException) {
            return redirect(route('users'))->with([
                'status' => 'error',
                'message' => __('user.missing'),
                'reason' => 'Not Found'
            ]);
        }
    }
}
