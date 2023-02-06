@component('mail::message')
<p class="center-text">
Your email {{ $email }} was changed to current.<br>
You need to confirm your new email.
</P>

@component('mail::button', ['url' => $link])
Confirm email
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent