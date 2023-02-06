@component('mail::message')
<p class="center-text">
Dear {{ $book->user->firstname }}.<br>
Good news! You have completed your Rapid experience!.
</p>

<p class="center-text">
We hope you enjoyed your time with our Pro Coach team.
</p>

<p class="center-text">
Your certificate is enclosed as recognition of your accomplishment, including your Rider Report and Confirmation Letter.
</p>

@component('mail::button', ['url' => $documents])
Check Certificate
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent