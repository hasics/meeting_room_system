@extends('layout')

@section('content')

    <div class="row">
        <div clss="col-lg-12">
            <ol class="breadcrumb">
                <li>You are here: <a href="{{ url('/') }}">Home</a></li>
                <li class="active"><a href="{{ url('/books') }}">Booking Request</a></li>
            </ol>
        </div>
    </div>

    @include('message')

    <div class="row">
        <div class="col-lg-12">
            @if($books->count() > 0)
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking's Title</th>
                        <th>Email</th>
                        <th>Room</th>
                        <th>Start</th>
                        <th>End</th>
                        @if (Auth::guest())
                            <th></th>
                        @else
                            <th>Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;?>
                    @foreach($books as $book)
                        <tr>
                            <th scope="row">{{ $i++ }}</th>
                            <td><a href="{{ url('books/' . $book->id) }}">{{ $book->title }}</a> <small>by {{ $book->name }}</small></td>
                            <td>{{ $book->email }}</td>
                            <td>{{ $book->room }}</td>
                            <td>{{ date("g:ia\, jS M Y", strtotime($book->start_time)) }}</td>
                            <td>{{date("g:ia\, jS M Y", strtotime($book->end_time)) }}</td>
                            @if (Auth::guest())

                            @else
                            <td>
                                <a title="Approve" class="btn btn-primary btn-sm" href="{{ url('/events/create', $book->id) }}">
                                    <span class="glyphicon glyphicon-ok"></span></a>
                                <form action="{{ url('/events/deny', $book->id) }}" style="display:inline" method="POST">
                                    <input type="hidden" name="_method" value="POST" />
                                    {{ csrf_field() }}
                                    <button title="Deny" class="btn btn-danger btn-sm" type="submit"><span class="glyphicon glyphicon-remove"></span></button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>No booking yet!</h2>
            @endif
        </div>
    </div>
@endsection
