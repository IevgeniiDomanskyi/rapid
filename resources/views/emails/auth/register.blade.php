@component('mail::message')
<p class="center-text">
Hi {{ $user->firstname }}.<br>
Thank you for registering your details with us. We’ll be in touch soon with all the information you need and answers to any questions you might have.
</p>

@component('mail::button', ['url' => $link])
Activate account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent