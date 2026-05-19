@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')

<div class="container">
    <h4 class="mb-3">Detail Produk</h4>

    <div class="card" style="width: 25rem;">
        
        @if($produk->foto)
            <img src="{{ asset('storage/'.$produk->foto) }}" 
                 class="card-img-top" 
                 alt="Foto Produk">
        @endif

        <div class="card-body">
            <h5 class="card-title">{{ $produk->nama }}</h5>

            <p class="card-text">
                <strong>Harga Beli:</strong> {{ $produk->harga_beli }} <br>
                <strong>Harga Jual:</strong> {{ $produk->harga_jual }} <br>
                <strong>Stok:</strong> {{ $produk->stok }} <br>
                <strong>User:</strong> {{ $produk->user->name ?? '-' }}
            </p>

            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>
</div>

@endsection