@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Pengguna</h1>

    <table class="w-full border rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Nama</th>
                <th class="p-2 text-left">Email</th>
                <th class="p-2 text-left">Role</th>
                <th class="p-2 text-left">Status</th>
                <th class="p-2 text-left">Total Karya</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr class="border-t">
                <td class="p-2">{{ $user->name }}</td>
                <td class="p-2">{{ $user->email }}</td>
                <td class="p-2 capitalize">{{ $user->role }}</td>
                <td class="p-2">
                    @if($user->subscription && $user->subscription->status === 'active')
                        <span class="text-green-600 font-semibold">Premium</span>
                    @else
                        <span class="text-gray-500">Basic</span>
                    @endif
                </td>
                <td class="p-2 font-semibold">
                {{ $user->karyas_count }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
