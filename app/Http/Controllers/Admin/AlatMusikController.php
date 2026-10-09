<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatMusik;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlatMusikController extends Controller
{
    public function index()
    {
        $alat = AlatMusik::orderBy('nama_alat')->get();

        return view(
            'admin.alat.index',
            compact('alat')
        );
    }

public function destroy($id)
{
    $alat = AlatMusik::findOrFail($id);

    $isBorrowed = \App\Models\DetailPeminjaman::where('id_alat', $id)->where('status_detail', 'dipinjam')->exists();
    if ($isBorrowed) {
        return redirect()
            ->route('admin.alat.index')
            ->with('error', 'Alat musik tidak dapat dihapus karena sedang dipinjam.');
    }

    if ($alat->peminjaman()->exists() || \App\Models\DetailPeminjaman::where('id_alat', $id)->exists()) {
        return redirect()
            ->route('admin.alat.index')
            ->with('error', 'Data alat tidak dapat dihapus dari database karena memiliki riwayat peminjaman. Data riwayat tetap dipertahankan untuk integritas laporan.');
    }

    if ($alat->foto_alat && file_exists(public_path('uploads/alat/'.$alat->foto_alat))) {
        @unlink(public_path('uploads/alat/'.$alat->foto_alat));
    }

    $alat->delete();

    return redirect()
        ->route('admin.alat.index')
        ->with('success', 'Data alat musik berhasil dihapus dari database.');
}

    public function create()
{
    return view('admin.alat.create');
}
public function store(Request $request)
{
$request->validate([

    'nama_alat' => 'required',

    'kategori' => 'required',

    'maks_lama_pinjam' => 'required|numeric|min:1',

    'foto_alat' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',

]);
$namaFoto = null;

if ($request->hasFile('foto_alat')) {

    $file = $request->file('foto_alat');

    $namaFoto = time().'_'.$file->getClientOriginalName();

    $file->move(
        public_path('uploads/alat'),
        $namaFoto
    );

}
        try {
            $alat = AlatMusik::create([
                'nama_alat' => $request->nama_alat,
                'kategori' => $request->kategori,
                'maks_lama_pinjam' => $request->maks_lama_pinjam,
                'status_alat' => 'Tersedia',
                'foto_alat' => $namaFoto,
            ]);

            $kode = 'ALT' . str_pad($alat->id_alat, 4, '0', STR_PAD_LEFT);

            $alat->update([
                'kode_alat' => $kode,
                'barcode_alat' => $kode,
            ]);

            return redirect()
                ->route('admin.alat.index')
                ->with('success', 'Data alat musik berhasil ditambahkan.');

        } catch (\Exception $e) {
            if ($namaFoto && file_exists(public_path('uploads/alat/' . $namaFoto))) {
                @unlink(public_path('uploads/alat/' . $namaFoto));
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan alat musik: ' . $e->getMessage());
        }
}
public function show($id)
{
    $alat = AlatMusik::findOrFail($id);

    return view(
        'admin.alat.show',
        compact('alat')
    );
}

public function cetakBarcode($id)
{
    $alat = AlatMusik::findOrFail($id);

    return view(
        'admin.alat.cetak_barcode',
        compact('alat')
    );
}

public function edit($id)
{
    $alat = AlatMusik::findOrFail($id);

    return view(
        'admin.alat.edit',
        compact('alat')
    );
}
public function update(Request $request, $id)
{
    $request->validate([
        'kode_alat' => [
            'required',
            Rule::unique('alat', 'kode_alat')->ignore($id, 'id_alat'),
        ],
        'nama_alat' => 'required',
        'kategori' => 'required',
        'maks_lama_pinjam' => 'required|numeric|min:1',
        'status_alat' => 'required',
        'foto_alat' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
    ], [
        'kode_alat.unique' => 'Kode alat musik tersebut sudah digunakan.',
    ]);

    $alat = AlatMusik::findOrFail($id);
$namaFoto = $alat->foto_alat;

if ($request->hasFile('foto_alat')) {

    if (
        $alat->foto_alat &&
        file_exists(public_path('uploads/alat/'.$alat->foto_alat))
    ) {

        unlink(public_path('uploads/alat/'.$alat->foto_alat));

    }

    $file = $request->file('foto_alat');

    $namaFoto = time().'_'.$file->getClientOriginalName();

    $file->move(
        public_path('uploads/alat'),
        $namaFoto
    );

}
    $alat->update([
        'kode_alat' => $request->kode_alat,
        'barcode_alat' => $request->kode_alat,
        'nama_alat' => $request->nama_alat,
        'kategori' => $request->kategori,
        'maks_lama_pinjam' => $request->maks_lama_pinjam,
        'status_alat' => $request->status_alat,
        'foto_alat' => $namaFoto,
    ]);

    return redirect()
        ->route('admin.alat.show', $alat->id_alat)
        ->with('success','Data alat berhasil diperbarui.');
}

public function findByBarcode($code)
{
    $alat = AlatMusik::where('barcode_alat', $code)
        ->orWhere('kode_alat', $code)
        ->first();

    if (!$alat) {
        return response()->json([
            'success' => false,
            'message' => 'Data alat musik tidak ditemukan.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data'    => [
            'id_alat'          => $alat->id_alat,
            'kode_alat'        => $alat->kode_alat,
            'barcode_alat'     => $alat->barcode_alat ?? $alat->kode_alat,
            'nama_alat'        => $alat->nama_alat,
            'kategori'         => $alat->kategori,
            'status_alat'      => $alat->status_alat,
            'maks_lama_pinjam' => $alat->maks_lama_pinjam,
            'foto_url'         => $alat->foto_alat ? asset('uploads/alat/'.$alat->foto_alat) : 'https://via.placeholder.com/250x300?text=Belum+Ada+Foto'
        ]
    ]);
}
}