<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranAnggota;
use App\Models\Anggota;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    // Menampilkan Form Pendaftaran
    public function create()
    {
        return view('pendaftaran.create');
    }

    // Menyimpan Data Pendaftaran
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftaran_anggota', 'nim')->where(function ($query) {
                    return $query->where('status_verifikasi', 'menunggu');
                }),
                Rule::unique('anggota', 'nim'),
            ],
            'prodi' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_hp' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pendaftaran_anggota', 'no_hp')->where(function ($query) {
                    return $query->where('status_verifikasi', 'menunggu');
                }),
                Rule::unique('anggota', 'no_hp'),
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                'regex:/^[a-zA-Z0-9._%+-]+@student\.unu-jogja\.ac\.id$/',
                Rule::unique('pendaftaran_anggota', 'email')->where(function ($query) {
                    return $query->where('status_verifikasi', 'menunggu');
                }),
                Rule::unique('anggota', 'email'),
            ],
            'foto_ktm' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar dalam sistem.',
            'prodi.required' => 'Program studi wajib diisi.',
            'alamat.required' => 'Alamat domisili wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.unique' => 'Nomor HP sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Gunakan Email Mahasiswa UNU (@student.unu-jogja.ac.id).',
            'email.unique' => 'Email sudah digunakan.',
            'foto_ktm.required' => 'Foto KTM wajib diunggah.',
            'foto_ktm.image' => 'File harus berupa gambar.',
            'foto_ktm.mimes' => 'Format foto KTM harus berupa JPG, JPEG, atau PNG.',
            'foto_ktm.max' => 'Ukuran foto KTM maksimal 2 MB.',
        ]);

        $namaFoto = null;

        try {
            DB::beginTransaction();

            // Upload Foto KTM
            if ($request->hasFile('foto_ktm')) {
                $foto = $request->file('foto_ktm');
                $namaFoto = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/ktm'), $namaFoto);
            }

            // Simpan ke database
            PendaftaranAnggota::create([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'foto_ktm' => $namaFoto,
                'status_verifikasi' => 'menunggu',
                'status_email' => 'pending',
                'catatan_admin' => null,
            ]);

            DB::commit();

            return redirect('/daftar')->with(
                'success',
                'Pendaftaran berhasil! Silakan menunggu proses verifikasi dari Admin Studio Musik.'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded file if exception occurred
            if ($namaFoto && file_exists(public_path('uploads/ktm/' . $namaFoto))) {
                @unlink(public_path('uploads/ktm/' . $namaFoto));
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan sistem saat menyimpan data pendaftaran. Silakan coba beberapa saat lagi.']);
        }
    }
}