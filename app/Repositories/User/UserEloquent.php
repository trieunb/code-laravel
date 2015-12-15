<?php
namespace App\Repositories\User;

use App\Models\Objective;
use App\Models\Qualification;
use App\Models\Question;
use App\Models\Reference;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserWorkHistory;
use App\Repositories\AbstractRepository;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;



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
        if (isset($data['status']))
            $user->status = $data['status'];
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
		if (isset($data['location']))
			$user->location = $data['location'];
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
		$data = $this->model
			->with(['user_educations' => function($q) {
                $q->orderBy('position');
            }, 'user_work_histories' => function($q) {
                $q->orderBy('position');
            }, 'questions' => function($q) {
            }, 'user_skills' => function($q) {
                $q->orderBy('position');
            }, 'references' => function($q) {
                $q->orderBy('position');
            }, 'objectives' => function($q) {
                $q->orderBy('position');
            }, 'qualifications' => function($q) {
                $q->orderBy('position');
            }
		 	])->findOrFail($user_id);

		$data->avatar = [
			'origin' => $data['avatar']['origin'] == null ? null: asset($data['avatar']['origin']),
			'thumb' => $data['avatar']['thumb'] == null ? null: asset($data['avatar']['thumb'])
		];
		$status = null;
		foreach (\Setting::get('user_status') as $k => $v) {
			if ($v['id'] == $data->status)
				$status = $v;
		}
		

		$data->status = $data->status != 0 && $data->status != null ? $status : null;

		return $data;
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
            'soft_skill' => \Setting::get('questions'),
            'location' => null,
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
        $avatar = isset($data['pictureUrls']['values']) ? [
            'origin' => $data['pictureUrls']['values'][0],
            'thumb' => $data['pictureUrls']['values'][0]]
        : null;
		return $this->model->create([
            'linkedin_id' => $data['id'],
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'email' => $data['emailAddress'],
            'avatar' => $avatar,
            'country' => $data['location']['name'],
            'link_profile' => $data['publicProfileUrl'],
            'soft_skill' => \Setting::get('questions'),
            'location' => null,
            'token' => $token
        ]);
	}

    public function updateUserFromOauth($data, $token, $id)
    {
        $user = $this->getById($id);

        $avatar = isset($data['pictureUrls']['values']) ? [
            'origin' => $data['pictureUrls']['values'][0],
            'thumb' => $data['pictureUrls']['values'][0]]
        : null;

        if (isset($data['id']))
            $user->linkedin_id = $data['id'];
        if (isset($data['firstName']))
            $user->firstname = $data['firstName'];
        if (isset($data['lastName']))
            $user->lastname = $data['lastName'];
        if (isset($data['emailAddress']))
            $user->email = $data['emailAddress'];
        if (isset($data['publicProfileUrl']))
            $user->link_profile = $data['publicProfileUrl'];
        if (isset($data['infomation']))
            $user->infomation = $data['infomation'];
        if (isset($data['birthday']))
            $user->dob = $data['birthday'];
        if (isset($data['gender']))
            $user->gender = $data['gender'];
        if (isset($data['pictureUrls']))
            $user->avatar = $avatar;
        if (isset($data['address']))
            $user->address = $data['address'];
        if (isset($data['soft_skill']))
            $user->soft_skill = $data['soft_skill'];
        if (isset($data['location']))
            $user->location = null;
        if (isset($data['phone-numbers']))
            $user->mobile_phone = $data['phone-numbers'];
        if (isset($data['home_phone']))
            $user->home_phone = $data['home_phone'];
        if (isset($data['city']))
            $user->city = $data['city'];
        if (isset($data['state']))
            $user->state = $data['state'];
        if (isset($data['location']))
            $user->country = $data['location']['name'];
        $user->token = $token;

        return $user->save();
    }
    
    /**
	 * Get template for user id
	 * @param  int $id
	 * @return mixed
	 */
	public function getTemplateFromUser($id) {
		return $this->model->with(['templates'])->findOrFail($id);
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

		$image = [ 
			'origin' => asset($user->avatar['origin']),
			'thumb' => asset($user->avatar['thumb'])
		];
		
		return $user->save() ? $image : '';
	}

	/**
	 * Edit Status
	 * @param  int $id     
	 * @param  int $status 
	 * @return bool         
	 */
	public function editStatus($id, $status)
	{
		if ( !in_array((int)$status, [1, 2, 3]))
			return null;

		$user = $this->getById($id);
		$user->status = $status;
		$result = $user->save();
		
		if ($result) {
			$status = null;
			foreach (\Setting::get('user_status') as $k => $v) {
				if ($v['id'] == $user->status)
					$status = $v;
			}
		}
		
		return $user->save() ? $status : null;
	}
    /**
     * Remove photo
     * @param  int $id 
     * @return bool     
     */
    public function removePhoto($id)
    {
        $user = $this->getById($id);

        try {
            \File::delete(public_path($user->avatar['origin']));
            \File::delete(public_path($user->avatar['thumb']));
            $user->avatar = ['origin' => null, 'thumb' => null];

            return $user->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function createUserFacebook($data, $token)
    {
        $avatar = isset($data['picture']) ? [
            'origin' => $data['picture']['data']['url'],
            'thumb' => $data['picture']['data']['url']
        ] : null;

        $birthday = isset($data['birthday'])
            ? Carbon::parse($data['birthday'])->format('Y-m-d')
            : false;
        $gender = '';
        if ($data['gender'] == "male")
            $gender = 0;
        elseif ($data['gender'] == "female")
            $gender = 1;
        else
            $gender = 2;

        return $this->model->create([
            'facebook_id' => $data['id'],
            'firstname' => $data['first_name'],
            'lastname' => $data['last_name'],
            'email' => isset($data['email']) ? $data['email'] : $data['id']."@facebook.com",
            'link_profile' => $data['link'],
            'gender' => $gender,
            'avatar' => $avatar,
            'soft_skill' => \Setting::get('questions'),
            'location' => null,
            'dob' => $birthday,
            'token' => $token
        ]);
    }

    public function updateUserFacebook($data, $token, $id)
    {

        $user = $this->getById($id);

        $avatar = [
            'origin' => $data['picture']['data']['url'],
            'thumb' => $data['picture']['data']['url']
        ];

        if (isset($data['id']))
            $user->facebook_id = $data['id'];
        if (isset($data['first_name']))
            $user->firstname = $data['first_name'];
        if (isset($data['last_name']))
            $user->lastname = $data['last_name'];
        if (isset($data['email']))
            $user->email = $data['email'];
        if (isset($data['link']))
            $user->link_profile = $data['link'];
        if (isset($data['gender']))
            $user->gender = $data['gender'];
        if (isset($data['picture']))
            $user->avatar = $avatar;
        $user->location = !$id ? null : !isset($data['location']) ? null: $data['location'];
        $user->soft_skill = \Setting::get('questions');
        if (isset($data['birthday']))
            $user->dob = Carbon::parse($data['birthday'])->format('Y-m-d');
        
        return $user->save();
    }

    /**
     * Get datatable of user
     * @return mixed 
     */
    public function dataTable()
    {
        return \Datatables::of($this->model->select([
            'id', 'firstname', 'lastname', 'address', 'email','dob',
            'created_at', 'updated_at'
            ]))
            ->addColumn('action', function($user) {
                /*return '<div class="btn-group" role="group" aria-label="...">
                <a class="delete-user btn btn-xs btn-danger" data-src="'.route('admin.user.delete',$user->id).'"><i class="glyphicon glyphicon-remove"></i> Delete</a>
                </div>';*/
                return '';
            })
            ->editColumn('firstname', function($user) {
                return $user->firstname . ' ' . $user->lastname;
            })
            ->editColumn('created_at', function($user) {
                return $user->created_at->format('Y-m-d');
            })
            ->editColumn('updated_at', function($user) {
                return $user->updated_at->format('Y-m-d');
            })
            ->removeColumn('lastname')
            ->make(true);
    }

    /**
     * Get Answers For User
     * @param  id $id 
     * @return Illuminate\Database\Eloquent\Collection     
     */
    public function answerForUser($id)
    {
        return $this->getById($id)->questions;
    }

    /**
     * Create Or Update point of Question 
     * @param int $id   
     * @throw \Exception
     */
    public function setPointForAnswer($id, $data)
    {
        $user = $this->getById($id);

        if ( ! count($user->questions()->sync(
            Question::prepareQuestionsForSave($data)))
        )
            throw new \Exception('Error when save.');
    }

    public function getSectionProfile($id, $section)
    {
        switch ($section) {
            case 'education':
                return json_encode(['data' => ['education' => UserEducation::whereUserId($id)->get()]]);
                break;
            case 'work':
                return json_encode(['data' => ['work' => UserWorkHistory::whereUserId($id)->get()]]);
                break;
            case 'reference':
                return json_encode(['data' => ['reference' => Reference::whereUserId($id)->get()]]);
                break;
            case 'key_qualification':
                return json_encode(['data' => ['key_qualification' => Qualification::whereUserId($id)->get()]]);
                break;
            case 'objective':
                return json_encode(['data' => ['objective' => Objective::whereUserId($id)->get()]]);
                break;
            case 'name': 
                return json_encode(['data' => $this->getById($id)->present()->name()]);
                break;
            case 'profile_website':
            case 'linkedin':
                return json_encode(['data' => $this->getById($id)->link_profile]);
                break;
            case 'availability':
                $status = null;
                foreach (\Setting::get('user_status') as $k => $v) {
                    if ($v['id'] == $this->getById($id)->status)
                        $status = $v;
                }
                return json_encode(['data' => $status]);
                break;
            case 'phone':
                return json_encode(['data' => $this->getById($id)->mobile_phone]);
                break;
            default:
                return json_encode(['data' =>$this->getById($id)->$section]);
                break;
        }
    }

    public function updateUserLogin($user, $token)
    {
        
        $this->model->update(['token' => $token], $user->id);
        return \Auth::login($user);
    }
}