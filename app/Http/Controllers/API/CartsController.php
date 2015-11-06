<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\Invoice\InvoiceInterface;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use Gloudemans\Shoppingcart\Exceptions\ShoppingcartInvalidItemException;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    /**
     * TemplateInterface
     * @var $template
     */
	private $template;

    /**
     * TemplateMarketInterface
     * @var $template_mk
     */
    private $template_mk;

    /**
     * InvoiceInterface
     * @var $invoice
     */
    private $invoice;

    /**
     * Constructor method
     * @param TemplateMarketInterface $template_mk 
     * @param TemplateInterface       $template    
     */
    public function __construct(TemplateMarketInterface $template_mk,
        TemplateInterface $template, InvoiceInterface $invoice)
    {
    	$this->middleware('jwt.auth');
		$this->template = $template;
        $this->template_mk = $template_mk;
        $this->invoice = $invoice;
    }

    public function postBuy($template_mk_id, Request $request)
    {
    	$template_mk = $this->template_mk->getDetailTemplateMarket($template_mk_id);
        try {
            \Cart::add($template_mk_id, $template_mk->title, 1, $template_mk->price);
            
            return response()->json(['status_code' => 200, 'status' => true, 'message' => 'Add to Cart successfully']);
        }catch (ShoppingcartInvalidItemException $e) {
            return response()->json(['status_code' => 400, 'staus' => false, 'message' => 'Error when add to Cart']);
        }  
    }

    public function checkout(Request $request)
    {
        return $this->invoice->checkout()
            ? response()->json(['status_code' => '200', 'message' => 'Checkout Cart successfully'])
            : response()->json(['status_code' => 400, 'message' => 'Error when checkout Cart']);
    }
}
