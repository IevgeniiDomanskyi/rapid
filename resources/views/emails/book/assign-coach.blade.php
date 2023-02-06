@component('mail::message')
<p class="center-text">
Hi {{ $user->firstname }},<br>
Good news! Your assigned Rapid Pro-Coach is
</p>

<p class="center-text">
{{ $coach->firstname }} {{ $coach->lastname }}<br>
{{ $coach->email }}<br>
{{ $coach->phone }}
</p>

<p class="center-text">
Don't hesitate to contact him if you have any questions regarding your booking.
</p>

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent