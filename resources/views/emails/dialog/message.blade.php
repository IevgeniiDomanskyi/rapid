@component('mail::message')
<p class="center-text">
Hi {{ $book->user->firstname }}.<br>
You've got mail! We've sent you a message regarding your Rapid training.
Please log in to My Rapid Journey on <a href="https://www.rapidtraining.co.uk">www.rapidtraining.co.uk</a> to view and respond. Thank you.
</p>

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent