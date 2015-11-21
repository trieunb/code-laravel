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
			'client_id'     => '1671697466387668',
			'client_secret' => '16338b2038ccf218bcd9dfcbec95e6d0',
			'scope'         => [],
		],
		'Linkedin' => [
		    'client_id'     => '75hhxk8f2nv2qr',
		    'client_secret' => 'qwzJn2mrckIqEHsO',
		],
		'Google' => [
		    'client_id'     => '939854112891-82p6b907hkp65os6en0149igdk5l2ieb.apps.googleusercontent.com',
		    'client_secret' => '3lk97XvW9yWMIDpkEnT_BWDm',
		    'scope'         => ['userinfo_email', 'userinfo_profile'],
		],  
	]

];