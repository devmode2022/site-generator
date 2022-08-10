<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteGeneratorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'sitename' => 'required',
            'admin_email' => 'required|email',
            'admin_user' => 'required',
            'admin_pass' => 'required'
        ];
    }

    /** @return array */
    public function messages()
    {
        return [
            'sitename.required' => 'The sitename field is required',
            'admin_email.required' => 'The admin email field is required',
            'admin_user.required' => 'The admin user field is required',
            'admin_pass.required' => 'The admin pass field is required'
        ];
    }
}
