<?php
namespace App\ValidatorApi;

use App\ValidatorApi\AbstractValidator;

class Objective_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'title' => 'required|min:3',
			'content' => 'required|min:3',
			'position' => 'required'
		];
	}
}