@props(['route'])

@php
$classes = Request::routeIs($route) ? 'bg-gray-800' : '';
@endphp

<a href="{{ route($route) }}" {{ $attributes->merge(['class' => "mx-2 text-white px-3 py-2 rounded hover:bg-gray-500 {$classes}"]) }}>{{ $slot }}</a>
