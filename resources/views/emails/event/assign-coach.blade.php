@component('mail::message')
<p class="center-text">
Hello!<br />
You were assigned to an event.
</p>

<p class="center-text">
<b>{{ $event->name }}</b><br />
{{ $event->from->format('d/m/Y') }} - {{ $event->to->format('d/m/y') }}<br />
{{ $event->region->title }}
</p>

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent