@extends('layout')

@section('content')

<div class="row">
	<div clss="col-lg-12">
		<ol class="breadcrumb">
			<li>You are here: <a href="{{ url('/') }}">Home</a></li>
			<li><a href="{{ url('/events') }}">Approved Events</a></li>
			<li class="active">Edit - {{ $event->title }}</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		
		@if($errors)
			@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
			@endforeach
		@endif
		
		<form action="{{ url('events/' . $event->id) }}" method="POST">
			{{ csrf_field() }}
			<input type="hidden" name="_method" value="PUT" />
			<div class="form-group @if($errors->has('name')) has-error has-feedback @endif">
				<label for="name">Your Name</label>
				<input type="text" class="form-control" name="name" value="{{ $event->name }}" placeholder="E.g. Pisyek">
				@if ($errors->has('name'))
					<p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
					{{ $errors->first('name') }}
					</p>
				@endif
			</div>
			<div class="form-group @if($errors->has('email')) has-error has-feedback @endif">
				<label for="email">Email Address</label>
				<input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ $event->email }}">
				@if ($errors->has('email'))
					<p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span>
						{{ $errors->first('email') }}
					</p>
				@endif
			</div>
			<div class="form-group @if($errors->has('title')) has-error has-feedback @endif">
				<label for="title">Purpose</label>
				<input type="text" class="form-control" name="title" value="{{ $event->title }}" placeholder="E.g. My event's title">
				@if ($errors->has('title'))
					<p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
					{{ $errors->first('title') }}
					</p>
				@endif
			</div>
			<div class="form-group @if($errors->has('room')) has-error has-feedback @endif">
				<label for="room">Room</label>
				<select class="form-control" name="room" value="{{ $event->room }}">
					<option selected hidden>{{ $event->room }}</option>
					<option>Room 1</option>
					<option>Room 2</option>
					<option>Room 3</option>
					<option>Room 4</option>
				</select>
				@if ($errors->has('room'))
					<p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span>
						{{ $errors->first('room') }}
					</p>
				@endif
			</div>
			<div class="form-group @if($errors->has('time')) has-error @endif">
				<label for="time">Time</label>
				<div class="input-group">
					<input type="text" class="form-control" name="time" value="{{ $event->start_time . ' - ' . $event->end_time }}" placeholder="Select your time">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
                    </span>
				</div>
				@if ($errors->has('time'))
					<p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
					{{ $errors->first('time') }}
					</p>
				@endif
			</div>
			<div class="form-group @if($errors->has('email')) has-error has-feedback @endif">
				<label for="other">Others</label>
				<input type="text" class="form-control" name="other"
					   placeholder="Please state any other equipments needed. E.g: projector, VGA cable, etc"
					   value="{{ $event->other }}">
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>		
	</div>
</div>
@endsection

@section('js')
<script src="{{ url('/js') }}/daterangepicker.js"></script>
<script type="text/javascript">
$(function () {
	$('input[name="time"]').daterangepicker({
		"timePicker": true,
		"timePicker24Hour": true,
		"timePickerIncrement": 15,
		"autoApply": true,
		"locale": {
			"format": "DD/MM/YYYY HH:mm:ss",
			"separator": " - ",
		}
	});
});
</script>
@endsection