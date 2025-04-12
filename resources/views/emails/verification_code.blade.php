@component('mail::message')
<b>Hello {{ $data['name'] }}</b>
<p>
    Your account created successfully. Please verifiy your account.
</p>
<p>
    Below is your verification code: <br />
    Code: {{ $verification_code }}
</p>
<p>
    Note: Do not share this with anyone
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent