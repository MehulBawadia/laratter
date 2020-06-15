@extends('admin.partials._layout')

@section('title')
    <title>Login Administrator | {{ config('app.name') }}</title>
@endsection

@section('content')
    <section class="px-3 my-5">
        <div class="container mx-auto">
            <h1>Welcome {{ auth()->user()->username }}</h1>
        </div>
    </section>
@endsection
