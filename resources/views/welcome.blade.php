@extends('partials._layout')

@section('title')
    <title>{{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="container mx-auto">
        <div class="h-screen flex items-center justify-center">
            <div class="px-5 py-5">
                <h1 class="text-center uppercase font-bold tracking-widest text-gray-600 text-2xl sm:text-3xl lg:text-5xl">
                    {{ config('app.name') }}
                </h1>

                <div class="flex flex-wrap justify-evenly mt-5">
                    <a href="{{ route('user.login') }}" class="text-gray-600 font-bold tracking-widest">Login</a>
                    <a href="{{ route('user.register') }}" class="text-gray-600 font-bold tracking-widest">Register</a>
                </div>
            </div>
        </div>
    </div>
@endsection
