<?php
namespace App\ValidatorApi;

class UserSkill_Rule extends AbstractValidator
{
	public function rules($params)
	{
		return [
			'skill_name' => 'required|min:3',
			'skill_test' => 'required|integer',
			'experience' => 'required|min:3',
			'position' => 'required'
		];
	}
}