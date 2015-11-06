<?php
namespace App\Repositories\Invoice;

use App\Repositories\Repository;

interface InvoiceInterface extends Repository
{
	/**
	 * Checkout Cart
	 * @return mixed       
	 */
	public function checkout();
}