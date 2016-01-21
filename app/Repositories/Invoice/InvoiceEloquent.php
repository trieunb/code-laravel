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
	public function checkout(array $data)
	{
		try {
			$invoice = new Invoice;
			$invoice->user_id = \Auth::user()->id;
			$invoice->status = 'pending';
			$invoice->total = $data['amount'];
			$result = $invoice->save();
			
			if ($result) {
				$invoice_details  = new InvoiceDetail;
				$invoice_details->invoice_id = $invoice->id;
				$invoice_details->template_market_id = $data['template_mk_id'];

				return $invoice_details->save() ? $invoice->id : false;
			}

			return false;
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

	/**
	 * Report for Admin
	 * @return mixed
	 */
	public function report($year = null)
	{
		$invoices = $this->model->select('id', \DB::raw('MONTH(paid_at) as month'), \DB::raw('COUNT(id) as count'))
			->groupBy('month')
			->orderBy('month');
			
		$invoices = is_null($year) 
			? $invoices->get()
			: $invoices->whereYear('created_at', '=', $year)->get();

		return getCountDataOfMonth($invoices);
	}
}