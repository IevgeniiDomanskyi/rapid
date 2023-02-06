@component('mail::message')
<p class="center-text">
Hi {{ $book->user->firstname }},<br>
Good news! Your booking is confirmed.
</p>

<p class="center-text">
You're on the way to a great rider coaching experience with Rapid.
A member of our Pro-Coach team will be in touch to finalise your dates,
discuss the best pick up point and line up the next steps.
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