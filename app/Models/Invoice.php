<?php

namespace App\Models;

use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'invoices';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice_details()
    {
    	return $this->hasOne(InvoiceDetail::class);
    }
}
