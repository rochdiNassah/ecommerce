<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\{View, Auth};
use App\Models\User;
use App\Notifications\UserApproved;
use App\Notifications\UserRejected;

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

            if ('pending' !== $user->status) {
                $response = [
                    'status' => 'warning',
                    'message' => 'This user is already active.',
                    'reason' => 'Already'
                ];

                return back()->with($response);
            }

            $user->status = 'active';
            $user->save();
            $user->notify((new UserApproved())->delay(now()->addMinutes(4)));

            $response = [
                'status' => 'success',
                'message' => 'Great! User approved and notified.'
            ];
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'error',
                'message' => 'Cannot approve non-existent user.',
                'reason' => 'Not Found'
            ];
        }

        return back()->with($response);
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

            if ('pending' === $user->status)
            $user->notify((new UserRejected())->delay(now()->addMinutes(4)));

            if (1 === $user->id && $request->user()->id !== 1)
            return back()->with([
                'status' => 'error',
                'message' => 'Cannot delete this user.',
                'reason' => 'Unauthorized'
            ]);

            $user->delete();

            $response = [
                'status' => 'success',
                'message' => 'User deleted.'
            ];
        } catch (ModelNotFoundException) {
            $response = [
                'status' => 'error',
                'message' => 'Cannot delete a non-existent user.',
                'reason' => 'Not Found'
            ];
        }

        return back()->with($response);
    }

    /**
     * Display the edit role screen.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRoleScreen(int $id)
    {
        return View::make('admin.update-user-role', ['user' => User::findOrFail($id)]);
    }

    /**
     * Upgrade or downgrade the given user.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request)
    {
        $data = $request->validate([
            'id' => ['required'],
            'role' => ['required', 'regex:(^admin$|^dispatcher$|^delivery_driver$)']
        ]);

        extract($data);

        $roles = [
            'admin' => 999,
            'dispatcher' => 666,
            'delivery_driver' => 333
        ];

        try {
            $user = User::findOrFail($id);

            $isUpgrade = $roles[$role] > $roles[$user->role];

            if ($role === $user->role) {
                $response = [
                    'status' => 'warning',
                    'message' => "This user is already {$role}.",
                    'reason' => 'Already'
                ];
            } else if ('admin' === $user->role  && 1 !== Auth::user()->id && $user->id !== Auth::user()->id) {
                $response = [
                    'status' => 'error',
                    'message' => 'Cannot downgrade this user.',
                    'reason' => 'Unauthorized'
                ];
            } else {
                $user->role = $role;
                $user->save();

                $response = [
                    'status' => 'success',
                    'message' => 'User' . ($isUpgrade ? ' upgraded ' : ' downgraded ') . 'successfully.'
                ];
            }
        } catch (ModelNotFoundException) {
            $response = [
                'status' => 'error',
                'message' => 'Cannot upgrade or downgrade a non-existent user.',
                'readon' => 'Not Found'
            ];
        }

        return back()->with($response);
    }
}
