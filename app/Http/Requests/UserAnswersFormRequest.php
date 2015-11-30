<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserAnswersFormRequest extends Request
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

        $rules = [];
        
        foreach ($this->get('points') as $key => $val) {

            $rules['points.'.$key] = 'numeric|between:0,100';
        }

        return $rules;
    }

}
