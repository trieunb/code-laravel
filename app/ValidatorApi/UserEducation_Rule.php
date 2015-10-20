<?php
namespace App\ValidatorApi;

class UserEducation_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'school_name' => 'required|min:3',
            'sub_title' => 'required|min:3',
            'start' => 'required|integer',
            'end' => 'required|integer',
            'degree' => 'required|min:3',
            'result' => 'required|min:3'
		];
	}
}