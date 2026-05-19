@extends('layouts.app')

@section('title', 'POS')

@section('content')

@if (session('errors'))
    <div class="alert alert-danger">
        {{ session('errors') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<h4 class="mb-3">
    {{ $mode == 'edit' ? 'Edit Penjualan' : 'Tambah Penjualan' }}
</h4>

<div class="row">

    {{-- ========================= PRODUK ========================= --}}
    <div class="col-md-6">

        <div class="card">

            <div class="card-body" style="max-height:70vh; overflow:auto">

                {{-- SEARCH --}}
                <div class="mb-3">

                    <form method="GET" action="{{ route('penjualan.create') }}">

                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Cari produk..."
                            onkeyup="this.form.submit()">

                    </form>

                </div>

                {{-- LIST PRODUK --}}
                @foreach($products as $product)

                <form method="POST"
                    action="{{ route('itempenjualan.store') }}"
                    class="row mb-2">

                    @csrf

                    <input type="hidden"
                        name="product_id"
                        value="{{ $product->id }}">

                    <div class="col-7">

                        <button type="submit"
                            class="btn btn-outline-primary w-100 text-start p-2
                            {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">

                            {{-- NAMA PRODUK --}}
                            <div class="fw-semibold">
                                {{ $product->nama }}
                            </div>

                            {{-- HARGA --}}
                            <small class="text-muted">
                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                            </small>

                        </button>

                    </div>

                    {{-- QTY --}}
                    <div class="col-3">

                        <input type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            class="form-control"
                            {{ $sale->status === 'COMPLETED' ? 'readonly' : '' }}>

                    </div>

                    {{-- BUTTON --}}
                    <div class="col-2">

                        <button type="submit"
                            class="btn btn-primary w-100
                            {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">

                            +

                        </button>

                    </div>

                </form>

                @endforeach

            </div>

        </div>

    </div>

    {{-- ========================= KERANJANG ========================= --}}
    <div class="col-md-6">

        <div class="card">

            <table class="table table-bordered mb-0">

                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th width="100">Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($sale->itemPenjualan as $item)

                    <tr>

                        {{-- PRODUK --}}
                        <td>
                            {{ $item->produk->nama }}
                        </td>

                        {{-- HARGA --}}
                        <td>
                            Rp {{ number_format($item->produk->harga_jual, 0, ',', '.') }}
                        </td>

                        {{-- QTY --}}
                        <td>

                            <form method="POST"
                                action="{{ route('itempenjualan.update', $item->id) }}">

                                @csrf
                                @method('PUT')

                                <input type="number"
                                    name="quantity"
                                    value="{{ $item->kuantitas }}"
                                    min="1"
                                    class="form-control form-control-sm"
                                    onchange="this.form.submit()"
                                    {{ $sale->status === 'COMPLETED' ? 'readonly' : '' }}>

                            </form>

                        </td>

                        {{-- SUBTOTAL --}}
                        <td>
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>

                        {{-- HAPUS --}}
                        <td>
                            @can('delete', $item)
                            <form method="POST"
                                action="{{ route('itempenjualan.destroy', $item->id) }}"
                                onsubmit="return confirm('Yakin ingin menghapus item?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm
                                    {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">

                                    Hapus

                                </button>

                            </form>
                            @endcan

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center text-muted">

                            Keranjang kosong

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

            {{-- FOOTER --}}
            <div class="card-footer">

                <h5 class="mb-3">
                    Total :
                    Rp {{ number_format($sale->itemPenjualan->sum('subtotal'), 0, ',', '.') }}
                </h5>

                {{-- CHECKOUT --}}
                <form method="POST"
                    action="{{ route('penjualan.update', $sale->id) }}"
                    onsubmit="return confirm('Yakin ingin checkout?')">

                    @csrf
                    @method('PUT')

                    <select name="payment_method"
                        class="form-select mb-2"
                        {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}>

                        <option value="">Pilih Pembayaran</option>

                        <option value="CASH">
                            CASH
                        </option>

                        <option value="QRIS">
                            QRIS
                        </option>

                    </select>

                    <button
                        class="btn btn-success w-100
                        {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">

                        Checkout

                    </button>

                </form>
                 
                @can('delete', $sale)
                <form method="POST"
                    action="{{ route('penjualan.destroy', $sale->id) }}"
                    class="mt-2"
                    onsubmit="return confirm('Yakin ingin membatalkan transaksi?')">

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-outline-danger w-100
                        {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">

                        Batalkan Transaksi

                    </button>

                </form>
                @endcan

            </div>

        </div>

    </div>

</div>

@endsection