<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\UserWorkHistory;
use App\Repositories\Category\CategoryEloquent;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Role\RoleEloquent;
use App\Repositories\Role\RoleInterface;
use App\Repositories\UserEducation\UserEducationEloquent;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Repositories\UserSKill\UserSkillEloquent;
use App\Repositories\UserSKill\UserSkillInterface;
use App\Repositories\UserWorkHistory\UserWorkHistoryEloquent;
use App\Repositories\UserWorkHistory\UserWorkHistoryInterface;
use App\Repositories\User\UserEloquent;
use App\Repositories\User\UserInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
	 /**
     * Register the service provider.
     *
     * @return void
     */
	public function register()
	{
		$this->app->bind(UserInterface::class, function() {
			return new UserEloquent(new User);
		});

		$this->app->bind(CategoryInterface::class, function() {
			return new CategoryEloquent(new Category);
		});

		$this->app->bind(RoleInterface::class, function() {
			return new RoleEloquent(new Role);
		});

		$this->app->bind(UserEducationInterface::class, function() {
			return new UserEducationEloquent(new UserEducation);
		});

		$this->app->bind(UserSkillInterface::class, function() {
			return new UserSkillEloquent(new UserSkill);
		});

		$this->app->bind(UserWorkHistoryInterface::class, function() {
			return new UserWorkHistoryEloquent(new UserWorkHistory);
		});
	}
}