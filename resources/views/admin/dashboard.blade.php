@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-4 gap-6">
    <a href="{{ route('admin.users') }}"
        class="block bg-white p-4 rounded shadow hover:bg-gray-50 transition">

        <p class="text-sm text-gray-500">Total User</p>
        <h2 class="text-2xl font-bold">{{ $totalUsers }}</h2>
    </a>
</div>
@endsection
