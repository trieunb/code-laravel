<?php
namespace App\Contract;

interface BraintreeContract 
{
	/**
	 * Get token 
	 * @return mixed 
	 */
	public static function getClientToken();

	/**
	 * Create Transaction
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public static function transaction(array $data);
}