@component('mail::message')
<p class="center-text">
A new ONLINE BOOKING has been completed. Please log in to RABS to retrieve.
</p>

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent