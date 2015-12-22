<?php

namespace App\ValidatorApi;

class UserWorkHistory_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'company' => 'required|min:3',
            'start' => 'required',
            'end' => 'required',
            'job_title' => 'required|min:3',
            'job_description' => 'required|min:3',
            'position' => 'required'
		];
	}
}