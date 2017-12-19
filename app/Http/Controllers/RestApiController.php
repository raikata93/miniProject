<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Traktor;
use App\Plot;
use App\WorkPlot;


class RestApiController extends Controller
{
    public function traktorStore(Request $request){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["Unknown token"]));    
    	}
    	
    	$requestData = json_decode($request->header('data'));
    	if(!isset($requestData->name) || $requestData->name == ""){
	        return response()->json(array('error'=>1,'result'=>["Empty traktor name"]));    
    	} else {
    		Traktor::create(['name' => $requestData->name]);
	        return response()->json(array('error'=>0,'result'=>["Sucessfull Added Traktor"]));    
    	}
    }

    public function plotStore(Request $request){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["Unknown token"]));    
    	}
    	
    	$requestData = json_decode($request->header('data'));
    	if(!isset($requestData->area) || $requestData->area == ""){
	        return response()->json(array('error'=>1,'result'=>["Invalid data for area"]));    
    	}

    	if(!isset($requestData->name) || $requestData->name == "" || !isset($requestData->culture) || $requestData->culture == ""){
	        return response()->json(array('error'=>1,'result'=>["All inputs are required"]));    
    	} else  {
    		Plot::create(['name' => $requestData->name, 'culture' => $requestData->culture, 'area' => $requestData->area]);
	        return response()->json(array('error'=>0,'result'=>["Sucessfull Added Plot"]));    
    	}
    }

    public function workPlotStore(Request $request, $plotId, $traktorId){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["Unknown token"]));    
    	}

    	$plot = Plot::find($plotId);
    	if (is_null($plot)) {
	        return response()->json(array('error'=>1,'result'=>["Unknown plot"]));    
    	}

		$traktor = Traktor::find($traktorId);
    	if (is_null($traktor)) {
	        return response()->json(array('error'=>1,'result'=>["Unknown traktor"]));    
    	}

    	$requestData = json_decode($request->header('data'));
    	if (!isset($requestData->date) || !isset($requestData->area)){
	        return response()->json(array('error'=>1,'result'=>["All inputs are required"]));    
    	}
		
		if (is_null($requestData->date) || is_null($requestData->area)){
	        return response()->json(array('error'=>1,'result'=>["All inputs are required"]));    
    	}    	

    	if($requestData->area > $plot->area){
	        return response()->json(array('error'=>1,'result'=>["Plot area which you choose is smaller than the need work area"]));    
    	}

    	WorkPlot::create(['date'=> $requestData->date, 'area'=> $requestData->area, 'traktor_id'=> $traktorId, 'plot_id' => $plotId]);

        return response()->json(array('error'=>0,'result'=>["Sucessfull Added WorkPlot"]));    
    }

    public function allWorkPlots(Request $request){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["Unknown token"]));    
    	}

    	$workPlots = WorkPlot::with('traktor','plot')->get();
    	if (is_null($workPlots)) {
	        return response()->json(array('error'=>1,'result'=>["Error"]));    
    	}
    	$array = [];
    	foreach ($workPlots as $workPlot) {
    		$row = [
    			'plot_name' => $workPlot->plot->name,
    			'culture' => $workPlot->plot->culture,
    			'date' => date('d/m/y',$workPlot->date),
    			'traktor_name' => $workPlot->traktor->name,
    			'area' => $workPlot->plot->area
    		];
    		array_push($array, $row);
    	}
        
        return response()->json(array('error'=>0,'result'=>[$array]));    
    }

    public function filterTable(Request $request,$filterId){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["Unknown token"]));    
    	}
    	if ($filterId>4 || $filterId<1) {
	        return response()->json(array('error'=>1,'result'=>["Unknown filter id"]));    
    	}
    	switch ($filterId) {
    		case 1: //ime na parcel
    		$result =  \DB::table('work_plots')
		           ->join('plots', 'work_plots.plot_id', '=', 'plots.id')
		           ->select('plots.name')
		           ->get();
    			break;
    		case 2:// kultura
    			$result =  \DB::table('work_plots')
		           ->join('plots', 'work_plots.plot_id', '=', 'plots.id')
		           ->select('plots.culture')
		           ->get();
    			break;
    		case 3: // data na obrabotvane
   				$result =  \DB::table('work_plots')
		           ->select('work_plots.date')
		           ->get();
    			break;
    		case 4: // ime na traktor
    			$result =  \DB::table('work_plots')
		           ->join('traktors', 'work_plots.traktor_id', '=', 'traktors.id')
		           ->select('traktors.name')
		           ->get();
    			break;
    	}
    		dd($result);
        return response()->json(array('error'=>0,'result'=>[$result]));    
    }
}
