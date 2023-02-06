@component('mail::message')
<p class="center-text">
Hi {{ $customer->firstname }}.<br>
Good news! Your booking is confirmed.
</p>

<p class="center-text">
<b>{{ $event->name }}</b><br />
{{ $event->from->format('d/m/Y') }} - {{ $event->to->format('d/m/Y') }}<br />
{{ $event->region->title }}
</p>

<p class="center-text">
In order for your event to be confirmed, you must click on the button below and sign the rider declaration.
</p>

@component('mail::button', ['url' => $declaration])
Rider Declaration
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent