<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\EditProfileRequest;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class UsersController extends Controller
{
	/**
	 * UserInterface
	 * @var [type]
	 */
	protected $user;

	/**
	 * UserEducationInterface
	 * @var [type]
	 */
	protected $user_education;

	public function __construct(UserInterface $user, UserEducationInterface $user_education)
	{
		$this->middleware('jwt.auth');

		$this->user = $user;
		$this->user_education = $user_education;
	}

	public function edit(EditProfileRequest $request)
	{
		$token = \JWTAuth::getToken();
		$user = \JWTAuth::toUser($token);
		
		if ( !$this->user->save($request, $user->id))
			return response(['status' => 'error', 'message' => 'Error when save infromation user'], 500);

		if( !$this->user_education->save($request, $user->id)) {
			return response(['status' => 'error', 'message' => 'Error when save information education user'], 500);
		}

		 return response()->json([
                'status' => true,
            ], 200);
	}
}
