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
	        return response()->json(array('error'=>1,'result'=>["user_not_founds"]));    
    	}

    	$workPlots = WorkPlot::with('traktor','plot')->get();
    	if (is_null($workPlots)) {
	        return response()->json(array('error'=>1,'result'=>["Error"]));    
    	}

        $array = WorkPlot::getAllWorkPlotsArray($workPlots);
        return response()->json(array('error'=>0,'result'=>[$array]));    
    }

    public function filterTable(Request $request){
    	$authUser = User::where('api_token', $request->header('token'))->first();
    	if(is_null($authUser)){
	        return response()->json(array('error'=>1,'result'=>["user_not_found"]));    
    	}

    	$result = WorkPlot::with('plot', 'traktor')->where('date',$request->work_date)->where(function ($q) use($request){
            $q->whereHas('plot', function($query) use ($request) {
                $query->where('name','like','%'.$request->plot_name.'%')
                      ->where('culture', 'like','%'.$request->culture.'%');
            })
            ->whereHas('traktor', function($qu) use ($request) {
                    $qu->where('name', 'like', '%'.$request->traktor_name.'%');
            });
        })->get();
        dd(json_encode($result));


        // return response()->json(array('error'=>0,'result'=>[json_encode($result)]));    
    }
}
