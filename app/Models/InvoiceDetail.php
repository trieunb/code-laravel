<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
	/**
	 * Table name
	 * @var string
	 */
    protected $model = 'invoice_details';

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
    	return $this->belongsTo(Invoice::class);
    }
}
