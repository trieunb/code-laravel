<?php
namespace App\Repositories\Invoice;

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
		$cart = \Cart::content();
		$invoice = new Invoice;
		$invoice->user_id = \Auth::user()->id;
		$invoice->status = 0;
		$invoice->total = \Cart::total();

		if ($invoice->save()) {

			$items = [];
			$template_mk_ids = [];
			
			foreach ($cart as $item) {
				$items['invoice_id'] = $invoice->id;
				$items['template_market_id'] = $item->id;
				$items['price'] = $item->price;
				$items['qty'] = $item->qty;

				$template_mk_ids[] = $item->id;
			}

			InvoiceDetail::saveMany($items);
			
			event(new FireContentForTemplate($template_mk_ids, $invoice_id->user_id));
			
			return \Cart::destroy();
		}
	}
}