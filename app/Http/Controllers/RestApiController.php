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
	        return response()->json(array('error'=>1,'result'=>["user_not_found"]));    
    	}

        $respData = array('error'=>1,'result'=>["unknown_data"]);
        if (Traktor::validateData($request->name)) {
            Traktor::create(['name' => $request->name]);
            $respData = array('error'=>0,'result'=>["sucessfull"]);
        }
        return response()->json($respData);
    }

    public function plotStore(Request $request){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["user_not_found"]));    
    	}
    	
        $respData = array('error'=>1,'result'=>["unknown_data"]);
        if (Plot::validateData($request->name, $request->culture, $request->area)) {
            Plot::create(['name' => $request->name, 'culture' => $request->culture, 'area' => $request->area]);
            $respData = array('error'=>0,'result'=>["sucessfull"]);
        }
        return response()->json($respData);
    }

    public function workPlotStore(Request $request){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["user_not_found"]));    
    	}

    	$plot = Plot::find($request->plot_id);
    	if (is_null($plot)) {
	        return response()->json(array('error'=>1,'result'=>["unknown_data"]));    
    	}

		$traktor = Traktor::find($request->traktor_id);
    	if (is_null($traktor)) {
	        return response()->json(array('error'=>1,'result'=>["unknown_data"]));    
    	}

        $respData = array('error'=>1,'result'=>["unknown_data"]);
        if (WorkPlot::validateData($request->traktor_id, $request->plot_id, $request->date, $request->area,$plot->area)) {
            
            WorkPlot::create(['date'=> $request->date, 'area'=> $request->area, 'traktor_id'=> $request->traktor_id, 'plot_id' => $request->plot_id]);
            $respData = array('error'=>0,'result'=>["sucessfull"]);
        }
        return response()->json($respData);	
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
    			'date' => date('d.m.Y',$workPlot->date),
    			'traktor_name' => $workPlot->traktor->name,
    			'area' => $workPlot->plot->area
    		];
    		array_push($array, $row);
    	}
        
        return response()->json(array('error'=>0,'result'=>[$array]));    
    }

    public function filterTable(Request $request){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["user_not_found"]));    
    	}
    	

    	// $result = '';
    	// switch ($filterId) {
    	// 	case 1: //ime na parcel
    	// 	$result =  \DB::table('work_plots')
		   //         ->join('plots', 'work_plots.plot_id', '=', 'plots.id')
		   //         ->select('plots.name')
		   //         ->get();
    	// 		break;
    	// 	case 2:// kultura
    	// 		$result =  \DB::table('work_plots')
		   //         ->join('plots', 'work_plots.plot_id', '=', 'plots.id')
		   //         ->select('plots.culture')
		   //         ->get();
    	// 		break;
    	// 	case 3: // data na obrabotvane
   		// 		$array = [];
   		// 		$result =  \DB::table('work_plots')
		   //         ->select('work_plots.date')
		   //         ->get();
		   //         foreach ($result as $date) {
		   //         		$formatedDate = date('d.m.Y', $date->date);
		   //         		array_push($array, $formatedDate);
		   //         }
		   //         $result = $array;
    	// 		break;
    	// 	case 4: // ime na traktor
    	// 		$result =  \DB::table('work_plots')
		   //         ->join('traktors', 'work_plots.traktor_id', '=', 'traktors.id')
		   //         ->select('traktors.name')
		   //         ->get();
    	// 		break;
    	// }

        return response()->json(array('error'=>0,'result'=>[json_encode($result)]));    
    }
}
