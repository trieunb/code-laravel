<?php

namespace App\Http\Requests;

class BulkPushNotifRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => 'required',
            'text' => 'required'
        ];
    }
}
