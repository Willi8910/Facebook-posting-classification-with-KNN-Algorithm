<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';
    protected $fillable = ['posting_id_neighbour'];

    public function Posting()
    {
    	return $this->belongsTo("App\Posting");
    }
}
