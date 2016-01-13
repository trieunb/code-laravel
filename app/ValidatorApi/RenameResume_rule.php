<?php
namespace App\ValidatorApi;

class RenameResume_rule extends AbstractValidator
{
    protected function rules($params)
    {
        return [
            'title' => 'required|max:255'
        ];
    }
}