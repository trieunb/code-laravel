<?php
namespace App\ValidatorApi;

class UserEducation_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'school_name' => 'required',
            'sub_title' => 'required',
            'start' => 'required|integer',
            'end' => 'required|integer',
            'degree' => 'required',
            'result' => 'required'
		];
	}
}