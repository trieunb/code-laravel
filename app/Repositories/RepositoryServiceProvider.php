<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Objective;
use App\Models\Qualification;
use App\Models\Reference;
use App\Models\Role;
use App\Models\Section;
use App\Models\Template;
use App\Models\TemplateMarket;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\UserWorkHistory;
use App\Repositories\Category\CategoryEloquent;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Invoice\InvoiceEloquent;
use App\Repositories\Invoice\InvoiceInterface;
use App\Repositories\Objective\ObjectiveEloquent;
use App\Repositories\Objective\ObjectiveInterface;
use App\Repositories\Qualification\QualificationEloquent;
use App\Repositories\Qualification\QualificationInterface;
use App\Repositories\Reference\ReferenceEloquent;
use App\Repositories\Reference\ReferenceInterface;
use App\Repositories\Role\RoleEloquent;
use App\Repositories\Role\RoleInterface;
use App\Repositories\Section\SectionEloquent;
use App\Repositories\Section\SectionInterface;
use App\Repositories\TemplateMarket\TemplateMarketEloquent;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateEloquent;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\UserEducation\UserEducationEloquent;
use App\Repositories\UserEducation\UserEducationInterface;
use App\Repositories\UserSkill\UserSkillEloquent;
use App\Repositories\UserSkill\UserSkillInterface;
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

		$this->app->bind(TemplateInterface::class, function() {
			return new TemplateEloquent(new Template);
		});

		$this->app->bind(TemplateMarketInterface::class, function() {
			return new TemplateMarketEloquent(new TemplateMarket);
		});

		$this->app->bind(ObjectiveInterface::class, function() {
			return new ObjectiveEloquent(new Objective);
		});

		$this->app->bind(ReferenceInterface::class, function() {
			return new ReferenceEloquent(new Reference);
		});

		$this->app->bind(InvoiceInterface::class, function() {
			return new InvoiceEloquent(new Invoice);
		});

		$this->app->bind(SectionInterface::class, function() {
			return new SectionEloquent(new Section);
		});

		$this->app->bind(QualificationInterface::class, function() {
			return new QualificationEloquent(new Qualification);
		});
	}
}