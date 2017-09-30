<?php

namespace Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Laravel\Http\Requests;
use Laravel\Http\Controllers\Controller;
use Laravel\Event;
use Laravel\Book;
use DateTime;
use Laravel\Mail\Approved;
use Laravel\Mail\Deny;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = [
			'page_title' => 'Approved Events',
			'events'	 => Event::orderBy('created_at')->paginate(10),
            'count'  => Book::count(),
		];
		
		return view('event/list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_by_booking($id)
    {
        $event = Book::findOrFail($id);
        $event->start_time =  $this->change_date_format_fullcalendar($event->start_time);
        $event->end_time =  $this->change_date_format_fullcalendar($event->end_time);

        $data = [
            'page_title' => 'Add new event',
            'event'		=> $event,
            'count'  => Book::count(),
        ];

        return view('event/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        //dd($request->toArray());
        $this->validate($request, [
            'name'	=> 'required|min:5|max:15',
            'title' => 'required|min:5|max:100',
            'room'  => 'required',
            'email' => 'required|email',
            'time'	=> 'required'
        ]);
		
		$time = explode(" - ", $request->input('time'));
		
		$event 					= new Event;
		$event->name			= $request->input('name');
		$event->title 			= $request->input('title');
        $event->email 			= $request->input('email');
        $event->room            = $request->input('room');
        $event->other			= $request->input('other');
		$event->start_time 		= $this->change_date_format($time[0]);
		$event->end_time 		= $this->change_date_format($time[1]);
		$event->save();
		
		$request->session()->flash('success', 'The event was successfully saved! An email have been sent to the user');

        Mail::to($event->email)->send(new Approved($event));

        $book = Book::find($id);
        $book->delete();

		return redirect('books');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$event = Event::findOrFail($id);
		
		$first_date = new DateTime($event->start_time);
		$second_date = new DateTime($event->end_time);
		$difference = $first_date->diff($second_date);

        $data = [
			'page_title' 	=> $event->title,
			'event'			=> $event,
			'duration'		=> $this->format_interval($difference),
            'count'  => Book::count(),
		];
		
		return view('event/view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
		$event->start_time =  $this->change_date_format_fullcalendar($event->start_time);
		$event->end_time =  $this->change_date_format_fullcalendar($event->end_time);
		
        $data = [
			'page_title' 	=> 'Edit '.$event->title,
			'event'			=> $event,
            'count'  => Book::count(),
		];
		
		return view('event/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
			'name'	=> 'required|min:5|max:15',
			'title' => 'required|min:5|max:100',
			'room'  => 'required',
			'time'	=> 'required'
		]);
		
		$time = explode(" - ", $request->input('time'));
		
		$event 					= Event::findOrFail($id);
		$event->name			= $request->input('name');
		$event->title 			= $request->input('title');
        $event->room            = $request->input('room');
        $event->start_time 		= $this->change_date_format($time[0]);
		$event->end_time 		= $this->change_date_format($time[1]);
		$event->save();
		
		return redirect('events');
    }

    public function deny(Request $request, $id)
    {
        $event = Book::findOrFail($id);

        $request->session()->flash('success', 'The request have been denied!');
        Mail::to($event->email)->send(new Deny($event));
//        dd($event->toArray());
        $event->delete();

        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
		$event->delete();
		
		return redirect('events');
    }
	
	public function change_date_format($date)
	{
		$time = DateTime::createFromFormat('d/m/Y H:i:s', $date);
		return $time->format('Y-m-d H:i:s');
	}
	
	public function change_date_format_fullcalendar($date)
	{
		$time = DateTime::createFromFormat('Y-m-d H:i:s', $date);
		return $time->format('d/m/Y H:i:s');
	}
	
	public function format_interval(\DateInterval $interval)
	{
		$result = "";
		if ($interval->y) { $result .= $interval->format("%y year(s) "); }
		if ($interval->m) { $result .= $interval->format("%m month(s) "); }
		if ($interval->d) { $result .= $interval->format("%d day(s) "); }
		if ($interval->h) { $result .= $interval->format("%h hour(s) "); }
		if ($interval->i) { $result .= $interval->format("%i minute(s) "); }
		if ($interval->s) { $result .= $interval->format("%s second(s) "); }
		
		return $result;
	}
}
