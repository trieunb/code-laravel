<?php
namespace App\ValidatorApi;

use App\ValidatorApi\AbstractValidator;

class Qualification_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'content' => 'required',
			'position' => 'required'
		];
	}
}