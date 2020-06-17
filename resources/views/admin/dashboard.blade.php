@extends('admin.partials._layout')

@section('title')
    <title>Welcome {{ auth()->user()->username }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    <section class="px-3 py-5 bg-white shadow">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold">Dashboard</h1>
        </div>
    </section>
@endsection
