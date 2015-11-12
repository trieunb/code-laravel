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
        $title = $this->has('id') ? ','.$this->get('id') : '';

        return [
            'title' => 'required|unique:template_markets,title'.$title,
        ];
    }
}
