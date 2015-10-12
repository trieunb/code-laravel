<?php
namespace App\ValidatorApi;

class RegisterForm_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:45',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:6',
        ];
	}
}