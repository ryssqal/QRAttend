@extends('admin.layout')

@section('content')
<h1 class="text-xl font-bold mb-4">Senarai Pengurus Majlis</h1>

<a href="{{ route('admin.event-managers.create') }}"
   class="bg-green-600 text-white px-3 py-2 rounded">
   + Tambah Pengurus
</a>

<table class="mt-4 w-full border">
    <tr class="bg-gray-100">
        <th class="border p-2">Nama</th>
        <th class="border p-2">Email</th>
        <th class="border p-2">Telefon</th>
    </tr>
    @foreach($managers as $manager)
    <tr>
        <td class="border p-2">{{ $manager->name }}</td>
        <td class="border p-2">{{ $manager->email }}</td>
        <td class="border p-2">{{ $manager->phone }}</td>
    </tr>
    @endforeach
</table>
@endsection
