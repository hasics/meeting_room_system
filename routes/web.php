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


Route::get('/', function () {
    $data = [
        'page_title' => 'Home',
        'count'      => DB::table('books')->count(),
    ];
//    dd(DB::table('events')->count());
    return view('event/index', $data);
});
Auth::routes();
Route::get('events/create/{id}', 'EventController@create_by_booking')->name('create');
Route::post('events/{id}','EventController@store');
Route::post('/events/deny/{id}','EventController@deny');
Route::resource('events', 'EventController');
Route::resource('books', 'BookController');


Route::get('/api', function () {
    $events = DB::table('events')->select('id', 'name', 'room', 'title', 'start_time as start', 'end_time as end')->get();
    foreach($events as $event)
    {
        $event->title = $event->room . ' - ' . $event->title . ' by ' . $event->name;
        $event->url = url('events/' . $event->id);
    }
    return $events;
});


