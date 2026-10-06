<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman registrasi.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $unitKerjas = UnitKerja::orderBy('nama_unit')->get();

        return view('auth.registrasi', compact('unitKerjas'));
    }

    /**
     * Proses pendaftaran pengguna baru.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:150'],
            'nip'           => ['required', 'string', 'max:30'],
            'username'      => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username'),
            ],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'unit_kerja_id' => ['required', 'exists:unit_kerjas,id'],
        ], [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'nip.required'           => 'NIP/NIK wajib diisi.',
            'username.required'      => 'Username wajib diisi.',
            'username.alpha_dash'    => 'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
            'username.unique'        => 'Username sudah digunakan, pilih yang lain.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'unit_kerja_id.required' => 'Unit kerja wajib dipilih.',
            'unit_kerja_id.exists'   => 'Unit kerja yang dipilih tidak valid dalam sistem.',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'nip'           => $request->nip,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'role'          => 'pegawai',
            'unit_kerja_id' => $request->unit_kerja_id,
            'is_active'     => true,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil sebagai Pegawai! Silakan masuk dengan akun baru Anda.');
    }
}
