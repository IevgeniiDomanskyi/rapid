@component('mail::message')
You were added as customer to RapidTraining service. Now you can login to you account using credentials:<br>
Login: <b>{{ $customer->email }}</b><br>
Password: <b>{{ $password }}</b><br>
You can change password at your next login.

@component('mail::button', ['url' => $link])
Login into account
@endcomponent

Best regards,<br>
{{ config('app.name') }}
@endcomponent