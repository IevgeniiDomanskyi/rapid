@component('mail::message')
<p class="center-text">
Hi {{ $user->firstname }}.
</p>

<p class="center-text">
You're on the way to a great rider coaching experience with Rapid.
</p>

@component('mail::button', ['url' => $link])
Enter payment details
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent