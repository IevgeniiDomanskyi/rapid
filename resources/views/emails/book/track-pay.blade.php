@component('mail::message')
<p class="center-text">
Hi {{ $book->user->firstname }}.<br>
Thanks for your payment of £{{ $amount }} and for choosing a Rapid training course. A summary of your booking is
outlined below and we're looking forward to seeing you on the day. It's great to have you with us.
</p>

<p class="center-text">
To get you ready for your course and into the Rapid mindset, please follow the link to our e-book "Performance Riding the Rapid Way"
</p>

<!-- @if ($book->trackDate)
<p class="center-text">
Your Track Date is {{ $book->trackDate->date->format('d/m/Y') }}
</p>
@endif

@if (count($dates))
<p class="center-text">
All road dates were agreed:
</p>

@foreach ($dates as $key => $date)
<p class="center-text">Date {{ $key }}: {{ $date }}</p>
@endforeach
@endif

<p class="center-text">
In order for your course to be confirmed, you must click on the button below and sign the rider declaration.
</p>

@component('mail::button', ['url' => $declaration])
Rider Declaration
@endcomponent -->

<p class="center-text">
Kind regards,<br>
The Rapid Training Team
</p>
@endcomponent