<?php
namespace App\Contracts;

interface BraintreeContract
{
	/**
	 * Get token
	 * @return mixed
	 */
	public static function getClientToken($user);

	/**
	 * Create Transaction
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public static function transaction(array $data);
}
