@component('mail::message')
<p class="center-text">
Hello!<br />
You were assigned to an track day.
</p>

<p class="center-text">
<b>{{ $trackDay->name }}</b><br />
{{ $trackDay->trackDate->date->format('d/m/Y') }}<br />
{{ $trackDay->trackDate->track->name }}<br>
{{ $trackDay->trackDate->track->region->title }}
</p>

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent