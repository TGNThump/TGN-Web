<?php

namespace TGN\Accounts;

use Illuminate\Database\Eloquent\Model;

class AccountEmail extends Model{

	protected $table = 'account_emails';

	protected $fillable = [
		'account_id',
		'email',
		'verify_hash',
		'public',
		'primary',
		'verified',
	];

	public function account(){
		return $this->belongsTo('TGN\Accounts\Account');
	}

}