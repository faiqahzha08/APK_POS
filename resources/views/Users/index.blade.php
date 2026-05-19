@extends('layouts.app')

@section('title', 'Users')

@section('content')

@include('layouts.navbar')

<h1>Halaman Users</h1>
@can('create', App\Models\Produk::class)
<a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">Create</a>
@endcan
<form action="{{ route('admin.users') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="form-control"
            placeholder="Cari berdasarkan nama atau email">
            <button class="btn btn-secondary">search</button>
    </div>
</form>

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Role</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
<tr>
    <td>{{ $users->firstItem() + $loop->index }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->role->name }}</td>
    <td>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
            Edit Akun
        </a>
        ||
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('yakin hapus user ini?')">
                Hapus
            </button>
        </form>
    </td>
</tr>
@endforeach
  </tbody>
</table>
{{ $users->links() }}
@endsection