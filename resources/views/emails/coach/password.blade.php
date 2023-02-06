@component('mail::message')
Your password was changed by administrator. Your new password is:<br>
Password: <b>{{ $password }}</b><br>
You can change password at your next login.

Best regards,<br>
{{ config('app.name') }}
@endcomponent