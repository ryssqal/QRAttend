@extends('admin.layout')

@section('content')
<h1 class="text-xl font-bold mb-4">Tambah Pengurus Majlis</h1>

<form method="POST" action="{{ route('admin.event-managers.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Nama"
        class="border p-2 w-full mb-2" required>

    <input type="email" name="email" placeholder="Email"
        class="border p-2 w-full mb-2" required>

    <input type="text" name="phone" placeholder="No Telefon"
        class="border p-2 w-full mb-2">

    <input type="password" name="password" placeholder="Password"
        class="border p-2 w-full mb-2" required>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>
@endsection
