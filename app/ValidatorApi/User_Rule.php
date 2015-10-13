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
            'avatar' => 'image',
            'dob' => 'required',
            'city' => 'required', 
            'state' => 'required',
            'country' => 'required'
		];
	}
}