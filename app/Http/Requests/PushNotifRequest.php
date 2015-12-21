<?php

namespace App\Http\Requests;

class PushNotifRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'text' => 'required'
        ];
    }
}
