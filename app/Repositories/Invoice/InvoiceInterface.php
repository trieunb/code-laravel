<?php
namespace App\Repositories\Invoice;

use App\Repositories\Repository;

interface InvoiceInterface extends Repository
{
	/**
	 * Checkout Cart
	 * @param array $data
	 * @return mixed       
	 */
	public function checkout(array $data);

	/**
	 * Paid invoice
	 * @param  int $invoice_id 
	 * @return bool     
	 */
	public function paid($invoice_id);

	/**
	 * Report for Admin
	 * @return mixed
	 */
	public function report();
}