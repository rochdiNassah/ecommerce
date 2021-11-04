<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = false;

    /**
     * Determine if the member is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullname' => ['required', 'min:2', 'max:256'],
            'email' => ['required', 'email', 'min:5', 'max:256', 'unique:members'],
            'phone_number' => ['required', 'min:10', 'max:15'],
            'password' => ['required', 'min:4', 'max:1600', 'confirmed'],
            'role' => ['required', 'regex:(^admin$|^dispatcher$|^delivery_driver$)'],
            'avatar' => ['image']
        ];
    }
}
