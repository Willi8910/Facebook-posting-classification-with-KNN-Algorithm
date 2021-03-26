<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggaran';
    public $timestamps = false;

    public function Posting()
    {
    	return $this->belongsToMany("App\Posting")->withPivot('id');
    }
}
