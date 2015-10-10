<?php
namespace App\ValidatorApi;

class UserEducation_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'school_name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'degree' => 'required',
            'result' => 'required'
		];
	}
}