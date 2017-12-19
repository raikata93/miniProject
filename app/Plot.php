<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    protected $table = 'plots';
    protected $fillable = ['name','culture','area'];
    public $timestamps = false;


    public function workPlots(){
    	return $this->hasMany('App\WorkPlot');
    }
}
