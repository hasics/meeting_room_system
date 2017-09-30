@component('mail::message')
  # Booking Request

  {{ $event->name }} has book {{ $event->room }}

  @component('mail::panel')
    Booking Time : {{ $event->start_time }} until {{ $event->end_time }}
  @endcomponent

  @component('mail::button', ['url' => 'http://localhost:8000/login', 'color' => 'red'])
    Login
  @endcomponent

@endcomponent