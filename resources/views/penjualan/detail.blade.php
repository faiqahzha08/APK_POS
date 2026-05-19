@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')

<div class="container">

    <h3 class="mb-4">Detail Penjualan</h3>

    {{-- DATA TRANSAKSI --}}
    <div class="card mb-4">
        <div class="card-header">
            Data Transaksi
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">ID Penjualan</th>
                    <td>{{ $penjualan->id }}</td>
                </tr>

                <tr>
                    <th>Kasir</th>
                    <td>{{ $penjualan->user->name }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ $penjualan->status }}</td>
                </tr>

                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ $penjualan->metode_pembayaran }}</td>
                </tr>

                <tr>
                    <th>Total Pembayaran</th>
                    <td>
                        Rp. {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <th>Tanggal</th>
                    <td>{{ $penjualan->created_at }}</td>
                </tr>

            </table>

        </div>
    </div>

    {{-- ITEM PRODUK --}}
    <div class="card">

        <div class="card-header">
            Item Produk
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($penjualan->itemPenjualan as $item)

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $item->produk->nama }}
                        </td>

                        <td>
                            Rp. {{ number_format($item->harga, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ $item->kuantitas }}
                        </td>

                        <td>
                            Rp. {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center">
                            Tidak ada item
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection