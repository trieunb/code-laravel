<?php
namespace App\Repositories\User;

use App\Events\GetCountryAndRegionFromLocationUser;
use App\Models\Objective;
use App\Models\Qualification;
use App\Models\Question;
use App\Models\Reference;
use App\Models\TemplateMarket;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserWorkHistory;
use App\Repositories\AbstractRepository;
use App\Repositories\User\UserInterface;
use App\Services\Report\Report;
use Carbon\Carbon;
use DB;
use Khill\Lavacharts\Lavacharts;
use Lava;



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
		if (isset($data['location']) && $data['location']['longitude'] != null)
			$user->location = $data['location'];
		if (isset($data['state']))
			$user->state = $data['state'];
		if (isset($data['country']))
			$user->country = $data['country'];

		if (array_key_exists('password', $data)) {
			$user->password = bcrypt($data['password']);
		}

        $result = $user->save();
        
		if ($result && $user->location != null) 
            event(new GetCountryAndRegionFromLocationUser($user));
        
        return $result;
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
            }, 'skills' => function($q) {
                $q->select('job_skills.id', 'job_skills.title')
                    ->orderBy('id');
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
            'email' => isset($data['emailAddress']) ? $data['emailAddress'] : $data['id'].'@linkedin.com',
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
		$user->avatar = $file->getClientOriginalName() == '' || $file->getClientOriginalName() == null
            ? null 
            : User::uploadAvatar($file);

		$image = [
			'origin' => asset($user->avatar['origin']),
			'thumb' => asset($user->avatar['thumb'])
		];

        if ( ! $user->save()) {
            return null;
        }

        return $user->avatar != null ? $image : null;
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
        $users = $this->model
            ->select('users.id', 'users.firstname', 'users.lastname', 'users.created_at', 'users.email', 'devices.platform')
            ->leftjoin('devices', 'users.id', '=', 'devices.user_id')
            ->whereDoesntHave('roles' , function($q) {
                $q->where('roles.slug', '=', 'admin');
            })->get();
        return \Datatables::of($users)
            ->addColumn('action', function($user) {
               return '<div class="btn-group text-center" role="group" aria-label="...">
                    <a class="btn btn-default" href="' .route('admin.user.get.detail', $user->id) . '"><i class="glyphicon glyphicon-eye-open"></i></a>
                </div>';
            })
            ->editColumn('firstname', function($user) {
                return $user->present()->name();
            })
            ->addColumn('checkbox', function($user) {
                return '<input type="checkbox" value="'.$user->id.'" />';
            })
            ->editColumn('created_at', function($user) {
                return $user->created_at->format('Y-m-d');
            })
            ->editColumn('os', function($user) {
                return $user->platform;
            })
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

    /**
     * Report user by month
     */
    public function reportUserMonth($year = null)
    {
        $user = $this->model->select(DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(id) AS count'))
                ->groupBy('month')
                ->orderBy('created_at', 'ASC')
                ->whereDoesntHave('roles', function($q) {
                    $q->where('roles.slug', 'admin');
                });

        return is_null($year)
            ? $user->get()
            : $user->whereYear('created_at', '=', $year)->get();
    }

    public function reportUserGender()
    {
        $gender = ['Male' => 0, 'Female' => 0, 'Other' => 0];
        $sql = 'CASE WHEN gender = 0 THEN "Male"
               WHEN gender = 1 THEN "Female"
               WHEN gender = 2 OR gender is null THEN "Other"
               END as "gender_user"';
        $report = new Report($this->model, $sql, 'gender_user');
        $report->setReportNotdAdmin(true);
        $options = [
            'is3D' => true,
            'width' => 988,
            'height' => 350,
            'sliceVisibilityThreshold' => 0
        ];
        return $report->prepareRender('gender_user', $gender, 'Reasons', 'Percent', $options);
    }

    public function reportUserAge()
    {
        $response = [];
        $groupAge = ['Younger than 20 years' => 0, '20 - 30 years' => 0, 'Older than 30 years' => 0];
        $sql = 'CASE
                WHEN FLOOR(DATEDIFF(now(), dob ) / 365) < 20 OR dob is null OR dob = "0000-00-00" THEN "Younger than 20 years"
                WHEN FLOOR(DATEDIFF(now(), dob) / 365) >= 20 AND FLOOR(DATEDIFF(now(), dob) / 365) <= 30 THEN "20 - 30 years"
                WHEN FLOOR(DATEDIFF(now(), dob) / 365) > 30 THEN "Older than 30 years"
                END as "group_age"';
        $report = new Report($this->model, $sql, 'group_age');
        $report->setReportNotdAdmin(true);
        $options = [
            'is3D' => true,
            'width' => 988,
            'height' => 350,
            'sliceVisibilityThreshold' => 0
        ];
        return $report->prepareRender('group_age', $groupAge, 'Reasons', 'Percent', $options);
    }

    public function reportUserRegion()
    {
        $report = new Report($this->model, 'region', 'region');
        $report->setReportNotdAdmin(true);
        $options = [ 
            'is3D' => true,
            'width' => 988,
            'height' => 350,
            'sliceVisibilityThreshold' => 0
        ];

        return $report->prepareRender('region', [], 'Reasons', 'Percent', $options);
    }

    public function reportUserOs()
    {
        $os = ['IOS' => 0, 'Android' => 0];
        $report = new Report($this->model, 'platform', 'platform');
        $report->setWith('devices');
        $report->setReportNotdAdmin(true);
        $options = [
            'is3D' => true,
            'width' => 988,
            'height' => 350,
            'sliceVisibilityThreshold' => 0
        ];

        return $report->prepareRender('platform', $os, 'Reasons', 'Percent', $options);
    }
}
