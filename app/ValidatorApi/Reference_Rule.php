<?php
namespace App\ValidatorApi;

use App\ValidatorApi\AbstractValidator;

class Reference_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'reference' => 'required|min:3',
			'content' => 'required|min:3'
		];
	}
}