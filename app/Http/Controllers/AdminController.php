<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Notifications\UserApproved;
use App\Notifications\UserRejected;

class AdminController extends Controller
{
    /**
     * Render dashboard view depending on user's role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        return view($request->user()->role.'.dashboard', [
            'usersCount' => User::all()->count(),
            'productsCount' => '0'
        ]);
    }

    /**
     * Display all users.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        return view('admin.users', ['users' => User::orderBy('status')->get()]);
    }

    /**
     * Approve a user under pending status.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveUser(Request $request, int $id)
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
     * Delete the given user.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUser(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($id);

            if ('pending' === $user->status)
            $user->notify((new UserRejected())->delay(now()->addMinutes(4)));

            if (1 === $user->id && $request->user()->id !== 1)
            return back()->with([
                'status' => 'error',
                'message' => 'This user cannot be deleted except by themselve.',
                'reason' => 'Unauthorized'
            ]);

            $user->delete();

            $response = [
                'status' => 'success',
                'message' => 'User deleted.'
            ];
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'error',
                'message' => 'Cannot delete non-existent user.',
                'reason' => 'Not Found'
            ];
        }

        return back()->with($response);
    }
}
