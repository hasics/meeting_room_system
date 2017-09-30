@component('mail::message')
  # Booking Status
  Your booking request for _"{{ $event->title}}"_ has been **Denied**

  Booking details :
  @component('mail::panel')
    Purpose: {{ $event->title }}
  @endcomponent
  @component('mail::panel')
    Room: {{ $event->room }}
  @endcomponent
  @component('mail::panel')
    Time: {{ $event->start_time }} until {{ $event->end_time }}
  @endcomponent

  @component('mail::button', ['url' => 'http://localhost:8000', 'color' => 'red'])
   Visit Website
  @endcomponent

  Thank you,<br>
  {{ config('app.name') }}
@endcomponent