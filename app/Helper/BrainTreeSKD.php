<?php
namespace App\Helper;

use Braintree\ClientToken;
use Braintree\Customer;
use Braintree\Transaction;

class BrainTreeSKD
{
	public static function getClientToken($user)
	{

		$obj = new static;

		$customerId = $obj->findOrCreateCustomer($user);

		if ( !$customerId) return false;
		
		return ClientToken::generate(['customerId' => $customerId]);
	}

	/**
	 * Create Customer 
	 * @param  mixed $user 
	 * @return int|false       
	 */
	private function findOrCreateCustomer($user)
	{
		$customer = Customer::find($user->id);
		

		if ( !$customer) {
			$result = Customer::create([
				'id' => \Auth::user()->id,
				'firstName' => $user->firstname,
				'lastName' => $user->lastName,
				'email' => $user->email,
				'phone'	=> $user->mobile_phone,			
			]);
			
			if ( ! $result->success) {
				return false;
			}

			return $result->customer->id;
		}
		
		return $customer->id;
	}

	public static function transaction(array $data)
	{
		if ( !isset($data['paymentMethodNonce']) || $data['paymentMethodNonce'] == "") 
			throw new PaymentMethodException('Payment method not valid');

		$result =  Transaction::sale([
			'customerId' => $data['customerId'],
			'amount' => $data['amount'],
			'paymentMethodNonce' => $data['paymentMethodNonce'],
		]);

		return $result;
	}
}