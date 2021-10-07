<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Notifications\JoinRequested;
use App\Models\User;

class RequestJoin extends Service
{
    private $data;
    protected $fileDestination = 'images/avatars';

    public function __construct(
        private $request
    ) {

    }

    /**
     * Store the users data.
     * 
     * @return void
     */
    public function store()
    {
        $this->extract();

        $user = User::create($this->data);

        $user->notify((new JoinRequested()));

        $this->response = [
            'status' => 'success',
            'message' => __('join.success')
        ];
    }

    private function extract()
    {
        $this->data = $this->request->safe()->except('avatar');

        $this->data['password'] = Hash::make($this->data['password']);

        if ($this->request->file('avatar')) {
            $this->file = $this->request->file('avatar');

            if (!$this->data['avatar_path'] = $this->storeFile()) {
                $this->failed();

                return;
            }
        }
    }

    private function failed()
    {
        $this->response = [
            'status' => 'error',
            'message' => __('global.failed')
        ];
    }
}