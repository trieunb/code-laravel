<?php
namespace App\ValidatorApi;

class ChangePass_Rule extends AbstractValidator
{
    protected function rules($params)
    {
        return [
            'old_pass' => 'required',
            'new_pass' => 'required|min:6',
        ];
    }
}