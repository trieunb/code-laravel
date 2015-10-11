<?php
namespace App\ValidatorApi;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

abstract class AbstractValidator
{
	/**
	 * @var Validation
	 */
	protected $validation;

	/**
	 * @var Illuminate\Validation\Factory
	 */
	protected $validator;

	/**
	 * @param ValidatorFactory $validator 
	 */
	public function __construct(ValidatorFactory $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * 
	 * @return array
	 */
	abstract protected function rules($params);

	/**
	 * @return MessageBag
	 */
	public function getValidationErrors()
	{
		return $this->validation->errors();
	}

	/**
	 * Validate data
	 * @param  array  $data 
	 * @param mixed $params
	 * @return true       
	 */
	public function validate(array $data, $params = null)
	{
		
		$this->validation = $this->validator->make($data, $this->rules($params));

		if ($this->validation->fails()) {
			throw new ValidatorAPiException('Validation failed', $this->getValidationErrors());
		}

		return true;

	}
}