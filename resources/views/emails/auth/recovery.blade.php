@component('mail::message')
<p class="center-text">
You have requested the new password!<br />
Your new password is:<br />
<b>Password</b>: {{ $password }}
</P>

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent
