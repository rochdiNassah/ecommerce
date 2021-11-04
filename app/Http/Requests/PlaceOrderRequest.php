<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
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
            'email' => ['required', 'email', 'min:5', 'max:256'],
            'phone_number' => ['required', 'min:10', 'max:15'],
            'address' => ['required', 'min:8', 'max:512'],
            'product_id' => ['required', 'exists:products,id']
        ];
    }
}
