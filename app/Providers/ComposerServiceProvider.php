<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoryComposer;
use App\Http\ViewComposers\QuestionComposer;
use App\Http\ViewComposers\UserComposer;
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
		\View::composer('admin.user.send-notification', UserComposer::class);
		\View::composer('user.*', function($view) {
	        $view->with('current_user', \Auth::user());
	    });
	}

	public function register()
	{
		
	}	
}