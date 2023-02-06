@component('mail::message')
<p class="center-text">
Hi {{ $book->user->firstname }}.<br>
Just to let you know the road dates with your Rapid Pro Coach are now confirmed.
</p>

<p class="center-text">
Your Rapid Pro Coach will be in touch, shortly, to arrange your meeting time and pick-up point.
</p>

<p class="center-text">
Everything is in place and we're looking forward to seeing you on the following dates:
</p>

@foreach ($dates as $key => $date)
<p class="center-text">
Date {{ $key }}: {{ $date }}
</p>
@endforeach

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
<i>Please note that postponement or cancellation with less than 48 hours notice may result in charges
(please refer to terms and conditions for full details).</i>
</p>

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent