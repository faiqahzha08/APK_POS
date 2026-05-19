@extends('layouts.app')

@section('title', 'Produk')

@section('content')

@include('layouts.navbar')

<h1>Halaman Produk</h1>

@can('create', App\Models\Produk::class)
<a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">Create</a>
@endcan

<form action="{{ route('produk.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="form-control"
            placeholder="Search nama produk"
        >
        <button class="btn btn-outline-secondary" type="submit">
            Search
        </button>
    </div>
</form>

<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>User</th>
      <th>Foto</th>
      <th>Nama</th>
      <th>Harga Beli</th>
      <th>Harga Jual</th>
      <th>Stok</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($products as $product)
    <tr>
        <td>{{ $products->firstItem() + $loop->index }}</td>

        <td>{{ $product->user->name ?? '-' }}</td>

        <td>
            @if($product->foto)
                <img src="{{ asset('storage/'.$product->foto) }}"
                     width="100"
                     class="img-thumbnail">
            @else
                <span>-</span>
            @endif
        </td>

        <td>{{ $product->nama }}</td>
        <td>{{ $product->harga_beli }}</td>
        <td>{{ $product->harga_jual }}</td>
        <td>{{ $product->stok }}</td>

        <td class="d-flex gap-1">
    
    @can('view', $product)
    <a href="{{ route('produk.show', $product->id) }}" 
       class="btn btn-info btn-sm">
        Detail
    </a>
    @endcan

    <a href="{{ route('produk.edit', $product->id) }}" 
       class="btn btn-warning btn-sm">
        Edit
    </a>

    <form action="{{ route('produk.destroy', $product->id) }}" 
          method="POST" 
          class="d-inline">
        @csrf
        @method('DELETE')

        <button class="btn btn-danger btn-sm"
            onclick="return confirm('Yakin hapus?')">
            Hapus
        </button>
    </form>
</td>
            
    @empty
    <tr>
        <td colspan="8" class="text-center">
            <h5>Data tidak tersedia.</h5>
        </td>
    </tr>
    @endforelse
  </tbody>
</table>

{{ $products->links() }}

@endsection