@component('mail::message')
# You Transaction has Been Confirmed

Hi, {{ $checkout->User->name }}
<br>
Your transaction has been confirmed, now you can enjoy the benefits of <b>{{ $checkout->Camp->title }}</b> Camp.

@component('mail::button', ['url' => route('user.dashboard')])
My Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
