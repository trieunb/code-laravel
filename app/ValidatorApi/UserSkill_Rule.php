<?php
namespace App\ValidatorApi;

class UserSkill_Rule extends AbstractValidator
{
	public function rules($params)
	{
		return [
			'id' => 'required'
		];
	}
}