<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkPlot extends Model
{
    protected $table = 'work_plots';
    protected $fillable = ['traktor_id','plot_id','date', 'area'];
    public $timestamps = false;


 	public function traktor(){
    	return $this->belongsTo('App\Traktor', 'traktor_id');
    }

    public function plot(){
    	return $this->belongsTo('App\Plot', 'plot_id');
    }

    public static function validateData($traktor, $plot, $date, $area, $plotArea){
    	if (is_null($traktor) || is_null($plot) || is_null($date) || is_null($area)) {
    		return false;
    	}else{
    		if ($area > $plotArea) {
    			return false;
    		}
    		
    		return true;
    	}
    }
}
