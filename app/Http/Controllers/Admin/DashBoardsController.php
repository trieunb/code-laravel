<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class DashBoardsController extends Controller
{


	public function index(Request $request)
	{
		return \Auth::user();
	}
} 