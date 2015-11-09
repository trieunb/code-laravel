<?php
namespace App\Repositories\Invoice;

use App\Events\FireContentForTemplate;
use App\Exceptions\CartException;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Repositories\AbstractRepository;
use App\Repositories\Invoice\InvoiceInterface;

class InvoiceEloquent extends AbstractRepository implements InvoiceInterface
{
	private $currency = 1.42533592;
	/**
	 * App\Models\Invoice
	 * @var Model
	 */
	protected $model;

	public function __construct(Invoice $invoice)
	{
		$this->model = $invoice;
	}

	/**
	 * Checkout Cart
	 * @return mixed       
	 */
	public function checkout(array $data)
	{
		try {
			//$cart = \Session::get('cart');

			$invoice = new Invoice;
			$invoice->user_id = \Auth::user()->id;
			$invoice->status = 'pending';
			$invoice->total = $data['amount'] * $this->currency;
			$result = $invoice->save();
			
			if ($result) {
				$invoice_details  = new InvoiceDetail;
				$invoice_details->invoice_id = $invoice->id;
				$invoice_details->template_market_id = $data['template_mk_id'];

				return $invoice_details->save();
			}

			return $invoice->id;
		} catch(CartException $e) {
			return false;
		}	
	}

	/**
	 * Paid invoice
	 * @param  int $invoice_id 
	 * @return bool     
	 */
	public function paid($invoice_id)
	{
		$invoice = $this->getById($invoice_id);
		$invoice->status = 'paid';
		$invoice->paid_at = \Carbon\Carbon::now();

		$result =  $invoice->save();

		if ($result) {
			event(new FireContentForTemplate(
				$invoice->invoice_details->template_market_id,
			 	$invoice->user_id)
			);
		}

		return $result;
	}
}