@component('mail::message')
<p class="center-text">
Hi {{ $book->user->firstname }}.<br>
Your Rapid coaching day is almost here. We're prepped and ready and can't wait to getyou started.
Your Pro Coach has all your details and will meet you at the pick-up point arranged.
Don't forget to go through your checklist of what we need you to bring and we'll see you in a couple of days.
</p>

@component('mail::button', ['url' => $declaration])
Sign Rider Declaration
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent