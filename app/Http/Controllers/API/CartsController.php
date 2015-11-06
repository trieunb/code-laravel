<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Http\Request;

class CartsController extends Controller
{
	private $template;

    public function __construct(TemplateInterface $template)
    {
    	$this->middleware('jwt.auth');
		$this->template = $template;
    }

    public function postBuy($template_mk_id, Request $request)
    {
    	
    }
}
