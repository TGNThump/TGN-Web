<?php

namespace TGN\Accounts;

use TGN\API\Resource;

class Account extends Resource{

	protected $table = 'accounts';

	protected $fillable = [
		'terratag',
		'first_name',
		'last_name',
		'password',
		'country',
		'gender',
		'date_of_birth',
		'bio',
		'tagline',
		'avatar_path',
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

	public function encode($options = array()){
		$isOwner = $this->getOption($options, 'isOwner', $this->isOwner());
		$scopes = $this->getOption($options, 'scopes', array());

		$result = array();
		$result['id'] = $this->id;
		$result['terratag'] = $this->terratag;

		$result['terratag'] = $this->terratag;
		switch($this->gender){
			case 'M':
				$result['gender'] = 'male';
				break;
			case 'F':
				$result['gender'] = 'female';
				break;
			case 'O':
				$result['gender'] = 'other';
		}

		$result['country'] = $this->country;
		$result['tagline'] = $this->tagline;
		$result['bio'] = $this->bio;
		$result['reputation'] = $this->reputation;
		
		if (empty($this->avatar_path)){
			$eobj = $this->emails()->orderBy('primary', 'desc')->first();
			if ($eobj != null){
				$email = $eobj->email;
				$hash = md5(strtolower(trim($email)));
				$path = "https://secure.gravatar.com/avatar/" . $hash . ".jpg?s=200";
				$header = get_headers($path . '&d=404', 1)[0];
				if ($header == "HTTP/1.1 200 OK"){
					$this->avatar_path = $path;
					$this->save();
				}
			}
		}

		if (empty($this->color)){
			if (!empty($this->avatar_path)){
				$rgb = ColorThief::getColor($this->avatar_path);
				$this->color = ''.sprintf('%02x', $rgb[0]) . sprintf('%02x', $rgb[1]) . sprintf('%02x', $rgb[2]);
				$this->save();
			} else {
				$this->color = substr(RandomColor::one(),1);
				$this->save();
			}
		}
		$result['color'] = '#' . $this->color;

		$result['urls'] = array();
		$result['urls']['profile'] = $this->app->config->get('app.www') . '/u/' . $this->terratag;
		$result['urls']['avatar'] = $this->avatar_path;

		if ($this->name_public || $this->hasScope($scopes, 'account.name') && $isOwner){
			$result['name'] = array(
				'formatted' => $this->first_name . ' ' . $this->last_name,
				'given' => $this->first_name,
				'family' => $this->last_name,
				'public' => $this->name_public === 1,
			);
		}

		if ($this->hasScope($scopes, 'account.emails') && $isOwner){
			$emails = array();

			foreach ($this->emails()->orderBy('primary', 'desc')->get() as $email){
				$emails[] = array(
					'value' => $email->email,
					'public' => ($email->public === 1),
					'primary' => ($email->primary === 1),
					'verified' => ($email->verified === 1),
				);
			}

			$result['emails'] = $emails;
		}

		if ($this->hasScope($scopes, 'account.dob') && $isOwner){
			$result['birthday'] = $this->date_of_birth;
		}

		$result['created_at'] = (new \DateTime($this->created_at))->format(\DateTime::ATOM);
		$result['updated_at'] = (new \DateTime($this->updated_at))->format(\DateTime::ATOM);
		$result['last_login_at'] = (new \DateTime($this->last_login_at))->format(\DateTime::ATOM);
		return $result;
	}

}