@component('mail::message')
<p class="center-text">
Hi {{ $book->user->firstname }}.<br>
You'll be pleased to know we've now confirmed your Bikemaster track date
and you're on your way to a great day of coaching with your fellow
riders and our Pro Coach Team.
</p>

<p class="center-text">
You will receive full joining instructions around 3 weeks prior to your event.
In the meantime, for more information about our home track please follow this link:
<a href="https://www.rapidtraining.co.uk/blyton-park">Https://www.rapidtraining.co.uk/blyton-park</a>
</p>

<p class="center-text">
<i>Please note that cancellation or postponement of track dates with less than 28 days notice may result in charges
(please refer to terms and conditions for full details).</i>
</p>

<p class="center-text">
Your track date is {{ $book->trackDate->date->format('d/m/Y') }}
</p>

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent