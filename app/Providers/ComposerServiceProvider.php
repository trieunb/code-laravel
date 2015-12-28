<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoryComposer;
use App\Http\ViewComposers\QuestionComposer;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

	public function boot()
	{
		\View::composer('admin.report.report_user', QuestionComposer::class);
		\View::composer([
			'admin.category.create',
			'admin.category.edit',
			'admin.template.create',
			'admin.template.edit'
		], CategoryComposer::class);
	}

	public function register()
	{
		
	}	
}