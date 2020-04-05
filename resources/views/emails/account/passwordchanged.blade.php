@component('mail::message')
# Hello,

Hello,
This is a notification email letting you know that your password on your account has been changed.

If this wasn't you, please contact us as soon as possible to get this resolved however, if this change was made by you please disregard this email.
@component('mail::button', ['url' => config("app.url")])
Contact Us
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
