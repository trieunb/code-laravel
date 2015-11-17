<?php

namespace App\ValidatorApi;

class UserWorkHistory_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'company' => 'required|min:3',
			'sub_title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'job_title' => 'required|min:3',
            'job_description' => 'required|min:3'
		];
	}
}