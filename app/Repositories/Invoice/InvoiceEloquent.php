<?php
namespace App\Repositories\Invoice;

use App\Models\Invoice;
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


}