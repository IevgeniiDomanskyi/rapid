@component('mail::message')
<p class="center-text">
Hi {{ $coach->firstname }}.<br>
Congratulations! You've been assigned a new course booking.
Please log in to RABS to retrieve the customer details,
so you can contact them immediately and then add your agreed dates to the system.
</p>

@component('mail::button', ['url' => $link])
Login to my Account
@endcomponent

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent