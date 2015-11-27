<?php
namespace App\ValidatorApi;

class UserEducation_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'school_name' => 'required|min:3',
            'start' => 'required',
            'end' => 'required',
            'degree' => 'required|min:3',
            'result' => 'required|min:3',
            'position' => 'required'
		];
	}
}