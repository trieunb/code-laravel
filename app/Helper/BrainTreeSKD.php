<?php
namespace App\Helper;

use Braintree\ClientToken;
use Braintree\Customer;
use Braintree\Transaction;

class BrainTreeSKD
{
	public static function getClientToken($user, $customerId = null)
	{

		$obj = new static;

		if ($customerId == null) {
			$customerId = $obj->createCustomer($user);
		}

		if ( !$customerId) return false;
		
		return ClientToken::generate(['customerId' => $customerId]);
	}

	/**
	 * Create Customer 
	 * @param  mixed $user 
	 * @return int|false       
	 */
	private function createCustomer($user)
	{
		$result = Customer::create([
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

	public static function transaction(array $data)
	{
		if ( !isset($data['paymentMethodNonce']) || $data['paymentMethodNonce'] == "") 
			throw new PaymentMethodException('Payment method not valid');

		$result =  Transaction::sale([
			'customerId' => $data['customerId'],
			'amount' => $data['amount'],
			'merchantAccountId' => env('BRAINTREE_MERCHARTID'),
			'paymentMethodNonce' => $data['paymentMethodNonce'],
		]);

		return $result;
	}
}