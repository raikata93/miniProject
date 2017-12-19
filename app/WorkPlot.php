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
}
