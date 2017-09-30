<?php

namespace Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Laravel\Book;
use DateTime;
use Laravel\Mail\Booking;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'page_title' => 'Booking Request',
            'books'	 => Book::orderBy('created_at')->get(),
            'count'  => Book::count(),
        ];
//        dd(Book::get()->toArray());
        return view('book/list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'page_title' => 'Book Meeting Room',
            'count'  => Book::count(),
        ];

        return view('book/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->toArray());
        $this->validate($request, [
            'name'	=> 'required|min:5|max:15',
            'title' => 'required|min:5|max:100',
            'email' => 'required|email',
            'room'  => 'required',
            'time'	=> 'required'
        ]);

        $time = explode(" - ", $request->input('time'));

        $event 					= new Book;
        $event->name			= $request->input('name');
        $event->email			= $request->input('email');
        $event->title 			= $request->input('title');
        $event->room            = $request->input('room');
        $event->other			= $request->input('other');
        $event->start_time 		= $this->change_date_format($time[0]);
        $event->end_time 		= $this->change_date_format($time[1]);
        $event->save();

        $request->session()->flash('success', 'The request was successfully saved! You will be notified by email later.');

        Mail::to('ina@gmail.com')->send(new Booking($event));
        return redirect('books/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Book::findOrFail($id);
//        dd($event->toArray());
        $first_date = new DateTime($event->start_time);
        $second_date = new DateTime($event->end_time);
        $difference = $first_date->diff($second_date);

        $data = [
            'page_title' 	=> $event->title,
            'event'			=> $event,
            'duration'		=> $this->format_interval($difference),
            'id'            => $id,
            'count'  => Book::count(),
        ];
//        dd($data);
        return view('book/view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($id)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, $id)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $event = Book::find($id);
        $event->delete();

        $request->session()->flash('success', 'The booking request has been deleted');
        return back();
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
