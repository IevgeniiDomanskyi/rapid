@component('mail::message')
<p class="center-text">
Hi {{ $coach->firstname }}.<br>
Congratulations, you're up and running. You are now added as a Pro Coach to the Rapid
Advanced Booking System (RABS), our bespoke internal system.
This gives you all-areas access to manage your time, your clients and the payments.
To get started use the password below, which you can change once registered.
</p>

<p class="center-text">
Username: <b>{{ $coach->email }}</b><br>
Password: <b>{{ $password }}</b><br>
</p>

<p class="center-text">
For security reasons, you will be invited to change your password when you will first log into RABS.
</p>

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent