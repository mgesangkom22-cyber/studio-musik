<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        if (session()->has('id_admin')) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
    public function login(Request $request)
    {
        $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);


    

        // Cari admin berdasarkan username
        $admin = Admin::where('username', $request->username)->first();

        // Cek username dan password
        if ($admin && $admin->password == $request->password) {

            // Simpan session
            Session::put('id_admin', $admin->id_admin);
            Session::put('nama_admin', $admin->nama_admin);

            return redirect('/dashboard');
        }

        // Jika gagal
        return back()->with('error', 'Username atau Password salah!');
    }
}