<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // =========================
    // HALAMAN PENJUALAN
    // =========================
    public function index(SearchRequest $request)
    {
        $keyword = $request->input('search');

        $sales = Penjualan::query()

            // 🔍 Search nama kasir
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjualan.index', compact('sales'));
    }

    // =========================
    // DETAIL PENJUALAN
    // =========================
    public function show(Penjualan $penjualan)
    {
        $penjualan->load('user', 'itemPenjualan.produk');

        return view('penjualan.detail', compact('penjualan'));
    }

    // =========================
    // CREATE TRANSAKSI
    // =========================
    public function create()
    {
        $sale = Penjualan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status' => 'OPEN'
            ],
            [
                'total_pembayaran' => 0,
                'metode_pembayaran' => 'CASH'
            ]
        );

        $products = Produk::orderBy('nama')->get();

        $mode = 'create';

        return view('penjualan.pos', compact(
            'sale',
            'products',
            'mode'
        ));
    }

    // =========================
    // EDIT TRANSAKSI
    // =========================
    public function edit(Penjualan $penjualan)
    {
        $sale = $penjualan;

        $products = Produk::orderBy('nama')->get();

        $mode = 'edit';

        return view('penjualan.pos', compact(
            'sale',
            'products',
            'mode'
        ));
    }

    // =========================
    // UPDATE / CHECKOUT
    // =========================
    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS'
        ]);

        // ❌ transaksi sudah selesai
        if ($penjualan->status !== 'OPEN') {

            return back()->with(
                'errors',
                'Transaksi sudah diproses'
            );
        }

        // ❌ keranjang kosong
        if ($penjualan->itemPenjualan()->count() === 0) {

            return back()->with(
                'errors',
                'Keranjang masih kosong'
            );
        }

        DB::transaction(function () use ($penjualan, $request) {

            // 🔄 hitung ulang total
            $total = $penjualan
                ->itemPenjualan()
                ->sum('subtotal');

            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran' => $total,
                'status' => 'COMPLETED'
            ]);
        });

        return redirect()
            ->route('penjualan.index')
            ->with(
                'success',
                'Transaksi berhasil diselesaikan'
            );
    }

    // =========================
    // HAPUS TRANSAKSI
    // =========================
    public function destroy(Penjualan $penjualan)
    {
        // 🔐 policy
        $this->authorize('delete', $penjualan);

        // ❌ hanya OPEN
        if ($penjualan->status !== 'OPEN') {

            return redirect()
                ->route('penjualan.index')
                ->with(
                    'errors',
                    'Transaksi sudah selesai tidak bisa dibatalkan'
                );
        }

        DB::transaction(function () use ($penjualan) {

            foreach ($penjualan->itemPenjualan as $item) {

                // 🔼 kembalikan stok
                $item->produk->increment(
                    'stok',
                    $item->kuantitas
                );
            }

            // ❌ hapus item
            $penjualan->itemPenjualan()->delete();

            // ❌ hapus transaksi
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with(
                'success',
                'Transaksi berhasil dibatalkan'
            );
    }
}