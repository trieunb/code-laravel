<?php
namespace App\Repositories\Invoice;

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
			$carts = \Cart::content();
			$invoice = new Invoice;
			$invoice->user_id = \Auth::user()->id;
			$invoice->status = 0;
			$invoice->total = \Cart::total();

			if ($invoice->save()) {

				$items = [];
				// $template_mk_ids = [];
				
				foreach ($carts as $cart) {
					$invoice_detail = new InvoiceDetail;
					$invoice_detail->template_market_id = $cart->id;
					$invoice_detail->price = $cart->price;
					$invoice_detail->qty = $cart->qty;
					
					$items[] = $invoice_detail;
					// $template_mk_ids[] = $item->id;
				}

				$invoice->invoice_details()->saveMany($items);
				
				// event(new FireContentForTemplate($template_mk_ids, $invoice_id->user_id));
				
				\Cart::destroy();

				return true;
			}
		} catch(CartException $e) {
			return null;
		}	
	}
}