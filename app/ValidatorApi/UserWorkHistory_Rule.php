<?php

namespace App\ValidatorApi;



class UserWorkHistory_Rule extends AbstractValidator
{
	protected function rules($params)
	{
		return [
			'company' => 'required',
			'sub_title' => 'required',
            'start' => 'required|integer',
            'end' => 'required|integer',
            'job_title' => 'required',
            'job_description' => 'required'
		];
	}
}