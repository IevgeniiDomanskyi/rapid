@component('mail::message')
Hello!

<p>
All road dates were approved, so you need to pay your course.
</p>

<p style="text-align: center;">
{{ $payment->book->course->title }}<br />
@if ($payment->book->level->level > 0)
Level {{ $payment->book->level->level }}: 
@endif
{{ $payment->book->level->title }}<br />
<b>&pound;{{ $payment->amount }}</b>
</p>

@component('mail::button', ['url' => $link])
Make a Payment
@endcomponent

Best regards,<br>
{{ config('app.name') }}
@endcomponent