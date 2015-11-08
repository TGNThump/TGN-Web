<?php

namespace TGN\Validation;

use Violin\Violin;
use TGN\Accounts\Account;
use TGN\Helpers\Hash;

class Validator extends Violin{
	
	// protected $user;
	// protected $hash;
	// protected $auth;

	// public function __construct(User $user, Hash $hash, $auth = null){
	// 	$this->user = $user;
	// 	$this->hash = $hash;
	// 	$this->auth = $auth;

	// 	$this->addFieldMessages([
	// 		'username' => [
	// 			'uniqueUsername' => 'That username is allready in use.'
	// 		]
	// 	]);

	// 	$this->addRuleMessages([
	// 		'matchesCurrentPassword' => 'That does not match your current password.'
	// 	]);
	// }

	// public function validate_uniqueUsername($value, $input, $args){
	// 	return ! (bool) $this->user->where('username', $value)->count();
	// }

	// public function validate_matchesCurrentPassword($value, $input, $args){
	// 	return ($this->auth && $this->hash->passwordCheck($value, $this->auth->password));
	// }
}