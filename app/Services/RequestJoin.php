<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Notifications\JoinRequested;
use App\Models\User;
use App\Interfaces\Responses\RequestJoinResponse;
use Illuminate\Support\Facades\Storage;

class RequestJoin extends BaseService
{
    private $data;

    /**
     * Store the users data.
     * 
     * @return void
     */
    public function store(): void
    {
        if (!$this->extract()) {
            return;
        }

        $user = User::create($this->data);

        $user->notify((new JoinRequested()));

        $response = [
            'status' => 'success',
            'message' => __('join.success'),
            'redirect_to' => route('login')
        ];

        $this->createResponse(RequestJoinResponse::class, $response);
    }

    /** @return bool */
    private function extract(): bool
    {
        $this->data = $this->request->safe()->except('avatar');
        $this->data['password'] = Hash::make($this->data['password']);

        if ($this->request->file('avatar')) {
            $this->file = $this->request->file('avatar');

            if (!$this->data['avatar_path'] = Storage::put('images/avatars', $this->file)) {
                $this->failed();

                return false;
            }
        }

        return true;
    }

    /**
     * Flash inputs to the session.
     * 
     * @return void
     */
    private function flashInputs()
    {
        $this->request->flashExcept('avatar', 'password');
    }

    /**
     * Request join failed.
     * 
     * @return void
     */
    private function failed(): void
    {
        $response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];

        $this->createResponse(RequestJoinResponse::class, $response);

        $this->flashInputs();
    }
}