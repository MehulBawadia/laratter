@component('mail::message')
# Registration successfull.

Find the details below that you had provided in order to become the user of {{ config('app.name') }}.

@component('mail::table')
| Particulars  | Details                   |
| :----------- | :------------------------ |
| First Name:  | {{ $user->first_name }}   |
| Last Name:   | {{ $user->last_name }}    |
| Username:    | {{ $user->username }}     |
| E-Mail:      | {{ $user->email }}        |
| Password:    | {{ session('password') }} |
@endcomponent

@component('mail::button', ['url' => route('user.dashboard')])
    Visit Your Dashboard
@endcomponent

Thank You.

Team **{{ config('app.name') }}**

@endcomponent
