<?php

namespace App\ValidatorApi;

use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Request;

class User_Rule extends AbstractValidator
{
      private $request;

      public function __construct(Request $request, ValidatorFactory $validator)
      {
            parent::__construct($validator);
            $this->request = $request;
      }

	protected function rules($params)
	{
            $rules = [];
            if (isset($this->request->get('user')['firstname']))
                  $rules['firstname'] = 'required';
            if (isset($this->request->get('user')['lastname']))
                  $rules['lastname'] = 'required';
            if (isset($this->request->get('user')['email']))
                  $rules['email'] = 'required|email|unique:users,email,'.$params;
            if (isset($this->request->get('user')['mobile_phone'])) 
                  $rules['mobile_phone'] = 'required';
            if (isset($this->request->get('user')['home_phone']))
                  $rules['home_phone'] = 'required';
            if (isset($this->request->get('user')['dob']))
                  $rules['dob'] = 'required';
            if (isset($this->request->get('user')['gender']))
                  $rules['gender'] = 'required';
            if (isset($this->request->get('user')['state']))
                  $rules['state'] = 'required|min:3';
            if (isset($this->request->get('user')['city']))
                  $rules['city'] = 'required|min:3';
            if (isset($this->request->get('user')['country']))
                  $rules['country'] = 'required|min:3';
		return $rules;
	}
}