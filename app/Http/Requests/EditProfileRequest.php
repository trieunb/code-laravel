<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditProfileRequest extends Request
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
        $token = \JWTAuth::getToken();
        $user = \JWTAuth::toUser($token);
        
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'dob' => 'required',
            'city' => 'required', 
            'state' => 'required', 
            'school_name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'degree' => 'required',
            'result' => 'required'
        ];

    }
}
