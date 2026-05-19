<?php

namespace App\Http\Controllers;

use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProdukController extends Controller
{
    public function index(SearchRequest $request)
    {
        $this->authorize('viewAny', Produk::class);

        $keyword = $request->input('search');

        if($keyword) {
            $products = Produk::when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', '%' . $keyword . '%');
            })
            ->orderBy('nama')
            ->paginate('10')
            ->withQueryString();
        } else {
            $products = Produk::latest()->paginate(10)->withQueryString();
        }

        return view('produk.index', compact('products'));
    }

    public function create()
    {
        $this->authorize('create', Produk::class);

        return view('produk.create');
    }

    public function store(StoreRequest $request)
{
    $dataReq = $request->validated();

    $data['user_id'] = Auth::id();
    $data['nama'] = $dataReq['name'];
    $data['harga_beli'] = $dataReq['purchase_price'];
    $data['harga_jual'] = $dataReq['selling_price'];
    $data['stok'] = $dataReq['stock'] ?? true;

    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('products', 'public');
    }

    Produk::create($data);

    return redirect()->route('produk.index')->with('success', 'Product created successfully.');
}
public function edit(Produk $produk)
{
    $this->authorize('update', $produk);
   
    return view('produk.edit', compact('produk'));
}

public function show(Produk $produk)
{
    return view('produk.show', compact('produk'));
}

public function update(UpdateRequest $request, Produk $produk)
{
    $this->authorize('update', $produk);

    $dataReq = $request->validated();

    $data = [
        'user_id' => Auth::id(),
        'nama' => $dataReq['name'],
        'harga_beli' => $dataReq['purchase_price'],
        'harga_jual' => $dataReq['selling_price'],
        'stok' => $dataReq['stock'],
    ];

    // Jika upload foto baru
    if ($request->hasFile('foto')) {

        // hapus foto lama (jika ada & memang tersimpan)
        if (
            $produk->foto &&
            Storage::disk('public')->exists($produk->foto)
        ) {
            Storage::disk('public')->delete($produk->foto);
        }

        // simpan foto baru
        $data['foto'] = $request->file('foto')->store('products', 'public');
    }

    $produk->update($data);

    return redirect()->route('produk.edit', $produk->id)->with('success', 'Product updated successfully.');
}

public function destroy(Produk $produk)
{
    $this->authorize('delete', $produk);

    if ($produk->foto) {
        Storage::disk('public')->delete($produk->foto);
    }

    $produk->delete();

    return redirect()->route('produk.index')->with('success', 'Product deleted successfully.');
}

}
