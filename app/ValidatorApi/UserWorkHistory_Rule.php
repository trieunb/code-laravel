<?php

namespace App\ValidatorApi;

class UserWorkHistory_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'company' => 'required',
            'work_history_start' => 'required',
            'work_history_end' => 'required',
            'job_title' => 'required',
            'job_description' => 'required'
		];
	}
}