@component('mail::message')
<p class="center-text">
Hi {{ $user->firstname }}.<br>
Good news! Your booking is confirmed.
</p>

<p class="center-text">
<b>{{ $trackDay->name }}</b><br />
{{ $trackDay->trackDate->date->format('d/m/Y') }}<br />
{{ $trackDay->trackDate->track->name }}<br>
{{ $trackDay->trackDate->track->region->title }}
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