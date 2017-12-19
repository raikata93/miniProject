<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Traktor extends Model
{
    protected $table = 'traktors';
    protected $fillable = ['name'];
	public $timestamps = false;



    public function workPlots(){
    	return $this->hasMany('App\WorkPlot');
    }
}
