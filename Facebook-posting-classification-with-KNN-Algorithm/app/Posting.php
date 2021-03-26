<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
	protected $table = 'posting';
	public $timestamps = false;

	public function Pelanggaran()
	{
		return $this->belongsToMany('App\Pelanggaran')->withPivot('id');
	}

	public function History()
	{
		return $this->hasOne('App\History');
	}
    //
}
