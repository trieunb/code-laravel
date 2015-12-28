<?php

namespace App\ValidatorApi;

class UserWorkHistory_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'company' => 'required',
            'start' => 'required',
            'end' => 'required',
            'job_title' => 'required',
            'job_description' => 'required',
            'position' => 'required'
		];
	}
}