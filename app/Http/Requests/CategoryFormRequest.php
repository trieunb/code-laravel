<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CategoryFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $inputs = array_map('trim', $this->all());
        $this->replace($inputs);
        
        $name = 'required|max:30|unique:categories,name';

        if ($this->has('id')) $name .= ','.$this->get('id');

        return [
            'name' => $name,
            'description' => 'max:255',
            'keyword' => 'max:255'
        ];
    }
}
