<?php

return [
	'app' => [
		'www' => '',
		'core' => '',
		'hash' => [
			'algo' => PASSWORD_BCRYPT,
			'cost' => 10
		]
	],

	'db' => [
		'driver' => '',
		'host' => '',
		'name' => '',
		'username' => '',
		'password' => '',
		'charset' => '',
		'colation' => '',
		'prefix' => ''
	],

	'auth' => [
		'session' => '',
		'remember' => ''
	],

	'twig' => [
		'debug' => false
	],

	'csrf' => [
		'key' => ''
	],

	'sendgrid' => [
		'token' => '',
		'from' => ''
	],

	'oauth' => [
		'tgn' => [
			'clientId' => '',
			'clientSecret' => '',
			'version' => ''
		],

		'facebook' => [
			'clientId' => '',
			'clientSecret' => '',
			'version' => ''
		],

		'google' => [
			'clientId' => '',
			'clientSecret' => ''
		],

		'github' => [
			'clientId' => '',
			'clientSecret' => ''
		]
	]
];