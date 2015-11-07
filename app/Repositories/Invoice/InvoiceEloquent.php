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
	public function checkout()
	{
		try {
			$cart = \Session::get('cart');

			$invoice = new Invoice;
			$invoice->user_id = \Auth::user()->id;
			$invoice->status = 0;
			$invoice->total = $cart['price'];

			if ($invoice->save()) {
				$invoice_details  = new InvoiceDetail;
				$invoice_details->invoice_id = $invoice->id;
				$invoice_details->template_market_id = $cart['template_market_id'];
				$invoice_details->price = $cart['price'];
				$invoice_details->qty = $cart['qty'];

				$result = $invoice_details->save();
				
				if ($result) {
					event(new FireContentForTemplate($cart['template_market_id'], $invoice->user_id));
					\Session::remove('cart');

					return $result;
				}

				return false;
			}
		} catch(CartException $e) {
			return false;
		}	
	}
}