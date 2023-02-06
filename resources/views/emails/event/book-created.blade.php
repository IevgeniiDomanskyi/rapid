@component('mail::message')
<p class="center-text">
Hi {{ $user->firstname }}.<br>
Good news! Your booking is confirmed.
</p>

<p class="center-text">
<b>{{ $event->name }}</b><br />
{{ $event->from->format('d/m/Y') }} - {{ $event->to->format('d/m/Y') }}<br />
{{ $event->region->title }}
</p>

@if ($request)
@component('mail::button', ['url' => $link])
Enter payment details
@endcomponent
@endif

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent