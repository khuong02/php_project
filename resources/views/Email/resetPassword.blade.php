@component('mail::message')
# Reset Password
Reset or change your password.
@component('mail::button', ['url' => 'http://127.0.0.1:8000/change-password/'.$token])
Change Password
@endcomponent
Link will expire after 1 hour.<br><br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
