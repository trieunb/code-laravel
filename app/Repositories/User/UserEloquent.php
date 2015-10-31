<?php
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\AbstractRepository;
use App\Repositories\User\UserInterface;



class UserEloquent extends AbstractRepository implements UserInterface
{
	protected $model;

	public function __construct(User $user)
	{
		$this->model = $user;
	}

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $user_id   
	 * @return mixed      
	 */
	public function saveFromApi($data, $user_id = null)
	{
		$user =  $this->getById($user_id);
		if (isset($data['firstname']))
			$user->firstname = $data['firstname'];
		if (isset($data['lastname']))
			$user->lastname = $data['lastname'];
		if (isset($data['email']))
			$user->email = $data['email'];
		if (isset($data['link_profile']))
			$user->link_profile = $data['link_profile'];
		if (isset($data['infomation']))
			$user->infomation = $data['infomation'];
		if (isset($data['dob']))
			$user->dob = $data['dob'];
		if (isset($data['gender']))
			$user->gender = $data['gender'];
		if (isset($data['address']))
			$user->address = $data['address'];
		if (isset($data['soft_skill']))
			$user->soft_skill = $data['soft_skill'];
		if (isset($data['mobile_phone']))
			$user->mobile_phone = $data['mobile_phone'];
		if (isset($data['home_phone']))
			$user->home_phone = $data['home_phone'];
		if (isset($data['city']))
			$user->city = $data['city'];
		if (isset($data['state']))
			$user->state = $data['state'];
		if (isset($data['country']))
			$user->country = $data['country'];
		
		if (array_key_exists('password', $data)) {
			$user->password = bcrypt($data['password']);
		}
		
		return $user->save();
	}

	/**
	 * Get profile
	 * @param  int $user_id 
	 * @return mixed     
	 */
	public function getProfile($user_id)
	{
		return $this->model
			->with(['user_educations', 'user_work_histories', 'user_skills', 'references', 'objectives'])
			->findOrFail($user_id);
	}

	/**
	 * save data Register user
	 * @param  mixed $request 
	 * @param  string $token 
	 * @return void       
	 */
	public function registerUser($request, $token)
	{
		$data = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => \Hash::make($request->input('password')),
            'soft_skill' => config('soft-skill.question'),
            'token' => $token,
        ];

        $this->model->create($data);
	}

	/**
	 * Create User get inforation to Oauth2
	 * @param  array $data  
	 * @param  string $token 
	 * @return mixed        
	 */
	public function createUserFromOAuth($data, $token)
	{
		return $this->model->create([
            'linkedin_id' => $data['linkedin_id'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'avatar' => $data['avatar'],
            'country' => $data['country'],
            'link_profile' => $data['link_profile'],
            'soft_skill' => config('soft-skill.question'),
            'token' => $token
        ]);
	}

    public function updateUserFromOauth($data, $token, $id)
    {
        $user = $this->getById($id);
        if (isset($data['firstname']))
            $user->firstname = $data['firstname'];
        if (isset($data['lastname']))
            $user->lastname = $data['lastname'];
        if (isset($data['email']))
            $user->email = $data['email'];
        if (isset($data['link_profile']))
            $user->link_profile = $data['link_profile'];
        if (isset($data['infomation']))
            $user->infomation = $data['infomation'];
        if (isset($data['dob']))
            $user->dob = $data['dob'];
        if (isset($data['gender']))
            $user->gender = $data['gender'];
        if (isset($data['avatar']))
            $user->avatar = $data['avatar'];
        if (isset($data['address']))
            $user->address = $data['address'];
        if (isset($data['soft_skill']))
            $user->soft_skill = $data['soft_skill'];
        if (isset($data['mobile_phone']))
            $user->mobile_phone = $data['mobile_phone'];
        if (isset($data['home_phone']))
            $user->home_phone = $data['home_phone'];
        if (isset($data['city']))
            $user->city = $data['city'];
        if (isset($data['state']))
            $user->state = $data['state'];
        if (isset($data['country']))
            $user->country = $data['country'];
        $user->token = $token;
        return $user->save();

    }
    
    /**
	 * Get template for user id
	 * @param  int $id
	 * @return mixed
	 */
	public function getTemplateFromUser($id) {
		return $this->model
            ->with(['templates'])
            ->findOrFail($id);
	}

    /**
     * get all template from market place
     */
    public function getAlltemplatesFromMarketPlace($user_id)
    {
        return $this->model
                ->with(['template_markets'])
                ->findOrFail($user_id);
    }

	/**
	 * Upload avatar
	 * @param  mixed $file    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function uploadImage($file, $user_id)
	{
		$user = $this->getById($user_id);
		$user->avatar = User::uploadAvatar($file);

		return $user->save() ? $user->avatar : '';
	}
}