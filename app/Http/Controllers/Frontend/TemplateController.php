<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
	private $template;

    public function __construct(TemplateInterface $template)
    {
    	$this->middleware('jwt.auth');
    	
		$this->template = $template;
    }

    public function detail($id)
    {
    	$user = \JWTAuth::toUser($request->get('token'));

		if ($user->id != $id) {
			return response()->json(['status_code' => 403,'status' => false, 'message' => 'access for denied'], 403);
		}
		$template = $this->template->getByid($id);

		return view()->make('frontend.template.view', compact('template'));
    }
}
