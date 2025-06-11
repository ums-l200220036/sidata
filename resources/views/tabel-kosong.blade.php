@extends('layouts.app')
@section('title', $indikatorTitle)
<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6 text-center">
        <h2 class="text-lg font-bold mb-4">{{ $indikatorTitle }}</h2>
        <p class="text-gray-600">{{ $message }}</p>
    </div>
</section>
</x-navbar>