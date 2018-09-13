<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Device;
use App\Data;
use Illuminate\Http\Request;
use Carbon\Carbon;

Route::get('/{language?}', function ($language = null) {
    App::setLocale($language);
    
    $devices = Device::all();
    $latestData = Data::latest()->get();
    $dateToday = Carbon::now();
    $today = $dateToday->format('d-m-Y');
    $monthAgo = $dateToday->subMonth()->format('d-m-Y');
    
    return view('index', compact('devices', 'latestData', 'today', 'monthAgo'));
});

Route::get('/data/{parameter}', function (Request $request, $parameter) {
    if (!in_array($parameter, ['ph', 'temp', 'tds', 'turbidity', 'gps', 'orp', 'battery-level', 'battery-charging']) || empty($request->query('start')) || empty($request->query('end'))) {
        return abort(404);
    }
    
    $start = Carbon::parse($request->query('start'));
    $end = Carbon::parse($request->query('end'))->addDay();
    
    if ($parameter == 'gps') {
        $select = ['x', 'y'];
    } else {
        $select = [$parameter, 'created_at'];
    }
    
    $data = Data::select($select)
        ->where('created_at', '>', $start)
        ->where('created_at', '<', $end)
        ->get();
    
    return $data;
});

Route::get('/saveData', function (Request $request) {
    $parameterList = ['ph', 'temp', 'tds', 'turbidity', 'orp', 'battery-level', 'battery-charging', 'x', 'y', 'gps', 'ns'];
    
    $data = new Data;
    
    foreach($parameterList as $parameter) {
        $data->$parameter = $request->query($parameter);
    }
    
    $data->save();
});