<?php

namespace TGN\Accounts;

use Illuminate\Database\Eloquent\Model;

class Account extends Model{

	protected $table = 'accounts';

	protected $fillable = [
		'terratag',
		'first_name',
		'last_name',
		'password',
		'country',
		'gender',
		'date_of_birth',
		'reputation',
		'active',
		'recover_hash',
		'remember_identifier',
		'remember_token',
		'last_login_at'
	];

	public function emails(){
		return $this->hasMany('TGN\Accounts\AccountEmail');
	}

}