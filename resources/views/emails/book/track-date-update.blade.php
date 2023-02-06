@component('mail::message')
<p class="center-text">
Hi {{ $book->user->firstname }}.<br>
Just to let you know we have now updated your track date and the booking has been amended by your Rapid Pro Coach.
The new date has been logged in My Rapid Journey for your records.
</p>

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent