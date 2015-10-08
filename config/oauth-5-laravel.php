<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Facebook' => [
			'client_id'     => '',
			'client_secret' => '',
			'scope'         => [],
		],
		'Linkedin' => [
		    'client_id'     => '75hhxk8f2nv2qr',
		    'client_secret' => 'qwzJn2mrckIqEHsO',
		],

	]

];