<?php
namespace App\Providers;

use Braintree\Configuration;
use Illuminate\Support\ServiceProvider;

class BraintreeServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}



	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		Configuration::environment(
			$this->app['config']->get('braintree.environment')
		);

		Configuration::merchantId(
			$this->app['config']->get('braintree.merchartId')
		);

		Configuration::publicKey(
			$this->app['config']->get('braintree.publicKey')
		);

		Configuration::privateKey(
			$this->app['config']->get('braintree.privateKey')
		);

		$encryptionKey = $this->app['config']->get('braintree.clientSideEncryptionKey');
	}
}