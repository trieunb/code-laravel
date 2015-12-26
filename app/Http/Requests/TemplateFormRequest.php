<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TemplateFormRequest extends Request
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
        $title = $this->has('id') ? ','.$this->get('id') : '';


        return [
            'title' => 'required|max:255',
            'cat_id' => 'required',
            'description' => 'max:1000',
            'price' => 'required|max_length_numeric:10|numeric|min:0',
            'version' => 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'price.max_length_numeric' => 'The :attribute may not be greater than 10 characters.'
        ];
    }
}
