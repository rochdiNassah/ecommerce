<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Notifications\JoinRequested;
use App\Models\User;

class RequestJoin extends Service
{
    private $data;
    protected $fileDestination = 'images/avatars';

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

        $this->response = [
            'status' => 'success',
            'message' => __('join.success')
        ];
    }

    /** @return bool */
    private function extract(): bool
    {
        $this->data = $this->request->safe()->except('avatar');
        $this->data['password'] = Hash::make($this->data['password']);

        if ($this->request->file('avatar')) {
            $this->file = $this->request->file('avatar');

            if (!$this->data['avatar_path'] = $this->storeFile()) {
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
        $this->response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];

        $this->flashInputs();
    }
}