@component('mail::message')
# Administrator Generated Successfully.

Find the details below that you had provided in order to become an Administrator.

@component('mail::table')
| Particulars  | Details                   |
| :----------- | :------------------------ |
| First Name:  | {{ $user->first_name }}   |
| Last Name:   | {{ $user->last_name }}    |
| Username:    | {{ $user->username }}     |
| E-Mail:      | {{ $user->email }}        |
| Password:    | {{ session('password') }} |
@endcomponent

@component('mail::button', ['url' => route('homePage')])
    Visit Your Dashboard
@endcomponent

Thank You.

Team **{{ config('app.name') }}**

@endcomponent
