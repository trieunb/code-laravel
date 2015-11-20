<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Objective;
use App\Models\Qualification;
use App\Models\Reference;
use App\Models\Role;
use App\Models\Template;
use App\Models\TemplateMarket;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\UserWorkHistory;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use Bican\Roles\Traits\HasRoleAndPermission;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class User extends Model implements AuthenticatableContract,                                    
                                    CanResetPasswordContract,
                                    HasRoleAndPermissionContract
{
    use Authenticatable, CanResetPassword, HasRoleAndPermission;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'linkedin_id',
        'firstname',
        'lastname',
        'email',
        'status',
        'link_profile',
        'infomation',
        'dob',
        'gender',
        'avatar',
        'address',
        'soft_skill',
        'mobile_phone',
        'home_phone',
        'city',
        'state',
        'country',
        'password',
        'token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'soft_skill' => 'json',
        'avatar' => 'json',
        'id' => 'int',
        'linkedin_id' => 'int',
        'gender' => 'int'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Override get exp_time_token
     * @param  string $date 
     * @return string       
     */
    public function getExpTimeTokenAttribute($date)
    {
        return strtotime($date);
    }

    /**
     * path folder uploads
     * @var string
     */
    public $path = 'uploads/';

    public $img_width_thumb = 200;
    public $img_height_thumb = 200;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_educations()
    {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */ 
    public function user_skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_work_histories()
    {
        return $this->hasMany(UserWorkHistory::class);
    }

    /**
     * User has many templates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function template_markets()
    {
        return $this->hasMany(TemplateMarket::class);
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function references()
    {
        return $this->hasMany(Reference::class);
    }

     /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualifications()
    {
        return $this->hasMany(Qualification::class);
    }

    /**
     * Rename Image after upload 
     * @param  mixed $request 
     * @return string          
     */
    public static function renameImage($request)
    {
        $filename = explode('.', $request->getClientOriginalName());

        return time().md5($filename[0]).'.'.end($filename);
    }

    public static function uploadAvatar(UploadedFile $file)
    {
        $avatar = new static;
        $name = $avatar->id.time().$file->getClientOriginalName();
        
        if ( !$file->move(public_path($avatar->path.'origin/'), $name)) {
            throw new UploadException('Error when save image');
        }

        $resize = \Image::make(public_path($avatar->path.'origin/'.$name))
            ->resize($avatar->img_width_thumb, $avatar->img_height_thumb)
            ->save(public_path($avatar->path.'thumb/').$name);
        
        if( !$resize) {
            throw new UploadException('Error when resize image');
        }

        return [
            'origin' => $avatar->path.'origin/'.$name, 
            'thumb' => $avatar->path.'thumb/'.$name
        ];
    }
    /**
     * A user can have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
}
