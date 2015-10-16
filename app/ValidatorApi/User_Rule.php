<?php

namespace App\ValidatorApi;

class User_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
		'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$params,
            'mobile_phone' => 'integer|min:9|max:11',
            'home_phone' => 'integer|min:9|max:11',
            'dob' => 'required',
            'gender' => 'required',
            'city' => 'required', 
            'state' => 'required',
            'country' => 'required'
		];
	}
}