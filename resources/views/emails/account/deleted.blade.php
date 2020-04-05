@component('mail::message')
# Hello,

This is an email letting you know that your account and all data associated with it has been deleted. We're sorry to see you go, but we hope you return one day!

If this wasn't you, please contact us as soon as possible to get this resolved however, if this change was made by you please disregard this email.
@component('mail::button', ['url' => config("app.url")])
Contact Us
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
