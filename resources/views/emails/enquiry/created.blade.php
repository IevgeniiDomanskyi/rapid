@component('mail::message')
<p class="center-text">
Dear {{ $enquiry->user->firstname }}.<br>
Good news! Your enquiry has been sent.
</p>

<p class="center-text">
A member of our Pro Coach team will be in touch shortly.
</p>

@if ($enquiry->is_guest)
<p class="center-text">
Please use this credentials to access your account.<br />
Email: <b>{{ $enquiry->user->email }}</b><br />
Password: <b>{{ substr(md5($enquiry->user->email), 0, 8) }}</b>
</p>
@endif

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent