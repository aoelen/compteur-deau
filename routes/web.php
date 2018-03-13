<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Device;
use App\Data;
use Illuminate\Http\Request;
use Carbon\Carbon;

Route::get('/', function () {
    $devices = Device::all();
    $latestData = Data::latest()->get();
    $dateToday = Carbon::now();
    $today = $dateToday->format('d-m-Y');
    $monthAgo = $dateToday->subMonth()->format('d-m-Y');
    
    return view('index', compact('devices', 'latestData', 'today', 'monthAgo'));
});

Route::get('/data/{parameter}', function (Request $request, $parameter) {
    if (!in_array($parameter, ['ph', 'temp', 'tds', 'turbidity', 'gps']) || empty($request->query('start')) || empty($request->query('end'))) {
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
