@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
The information in this email is confidential and solely for the use of the intended recipient(s). If you receive this email in error, please notify the sender and delete the email from your system immediately. In such circumstances, you must not make any use of the email or its contents.<br />
Computer viruses may be transmitted by email. Rapid Training Limited accepts no liability for any damage caused by any virus transmitted by this email. E-mail transmission cannot be guaranteed to be secure or error-free. It is possible that information may be intercepted. Corrupted, lost, destroyed, arrive late or incomplete, or contain viruses. The sender does not accept liability for any errors on omissions in the contents of this message, which arise as a result of e-mail transmission.<br /><br />
Rapid Training Limited.<br />
Registered Office: Scottsdale House 10-31 Springfield Avenue Harrogate HG1 2HR Registered in England. Registered number: 03389719<br />
Please consider the environment before printing this e-mail
@endcomponent
@endslot
@endcomponent
