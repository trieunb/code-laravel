<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserFormRequest extends Request
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
        if ($this->has('user')) {
            $rules = [];
            if ($this->has('firstname'))
                  $rules['firstname'] = 'required|min:3';
            if ($this->has('lastname'))
                  $rules['lastname'] = 'required|min:3';
            if ($this->has('email'))
                  $rules['email'] = 'required|email|unique:users,email,'.$this->get('id');
            if ($this->has('mobile_phone')) 
                  $rules['mobile_phone'] = 'integer|min:9|max:11';
            if ($this->has('home_phone'))
                  $rules['home_phone'] = 'integer|min:9|max:11';
            if ($this->has('dob'))
                  $rules['dob'] = 'required';
            if ($this->has('gender'))
                  $rules['gender'] = 'required';
            if ($this->has('state'))
                  $rules['state'] = 'required|min:3';
            if ($this->has('city'))
                  $rules['city'] = 'required|min:3';
            if ($this->has('country'))
                  $rules['country'] = 'required|min:3';
            return $rules;
        }
            
        return [];
    }
}
