<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartsController extends Controller
{
	private $template;

    public function __construct($PROPERTY)
    {
    	$this->middleware('jwt.auth');
		$this->PROPERTY = $PROPERTY;
    }

    public function buy(Request $request)
    {
    	
    }
}
