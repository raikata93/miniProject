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

    public static function validateData($plotName, $plotCulture, $plotArea){
    	if (is_null($plotName) || is_null($plotCulture) || is_null($plotArea)) {
    		return false;
    	}else{
    		return true;
    	}
    }
}
