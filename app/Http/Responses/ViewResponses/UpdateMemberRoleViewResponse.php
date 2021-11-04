<?php declare(strict_types=1);

namespace App\Http\Responses\ViewResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;
use App\Models\Member;

class UpdateMemberRoleViewResponse implements Responsable
{
     /** @var int */
     private $member_id;
 
     /** @param  int  $member_id */
     public function __construct(int $member_id)
     {
         $this->member_id = $member_id;
     }

    /**
     * Create an HTTP response that represents the object.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function toResponse($request): View
    {
        return view('admin.member.update-role', [
            'member' => Member::findOrFail($this->member_id)
        ]);
    }
}