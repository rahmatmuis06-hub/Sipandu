<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssetTetap;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // ────────────────────────────────────────────────────────────────────
    // Halaman Login
    // ────────────────────────────────────────────────────────────────────
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        // 1. Hitung total BMN (Gabungan Aset Tetap & Persediaan, bisa Anda sesuaikan)
        $totalAset = AssetTetap::count() + Persediaan::count();

        // 2. Hitung persentase Aset Tetap dengan Kondisi "Baik"
        $asetTotal = AssetTetap::count();
        // Catatan: Sesuaikan string 'Baik' dengan nama data kondisi di database Anda
        $asetBaik = AssetTetap::where('kondisi', 'Baik')->count(); 
        $persentaseBaik = $asetTotal > 0 ? round(($asetBaik / $asetTotal) * 100, 1) : 0;

        // 3. Hitung jumlah Pengguna Aktif
        $penggunaAktif = User::where('is_active', true)->count();

        // 4. Hitung jumlah Peran/Role yang saat ini terpakai di sistem
        $peranTersedia = User::distinct('role')->count('role');

        return view('auth.login', [
            'alreadyLoggedIn' => false,
            'totalAset' => $totalAset,
            'persentaseBaik' => $persentaseBaik,
            'penggunaAktif' => $penggunaAktif,
            'peranTersedia' => $peranTersedia,
        ]);
    }

    // ────────────────────────────────────────────────────────────────────
    // Proses Login
    // ────────────────────────────────────────────────────────────────────
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = Str::lower($request->input('username')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
        }

        $user = User::where('username', $request->input('username'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($throttleKey, 60);
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Username atau password salah.']);
        }

        if (!$user->is_active) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Akun tidak aktif. Hubungi admin.']);
        }

        RateLimiter::clear($throttleKey);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    // ────────────────────────────────────────────────────────────────────
    // Logout
    // ────────────────────────────────────────────────────────────────────
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->away(route('login', [], false))
            ->with('success', 'Anda telah logout.');
    }

    // ────────────────────────────────────────────────────────────────────
    // Redirect by Role
    // ────────────────────────────────────────────────────────────────────
    private function redirectByRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'superadmin'      => redirect()->route('superadmin.dashboard'),
            'kepalabpmp'      => redirect()->route('kepalabpmp.dashboard'),
            'kasubag'         => redirect()->route('kasubag.dashboard'),
            'adminpersediaan' => redirect()->route('adminpersediaan.dashboard'),
            'adminsarpras'    => redirect()->route('adminsarpras.dashboard'),
            'adminasettetap'  => redirect()->route('adminasettetap.dashboard'),
            'pegawai'         => redirect()->route('pegawai.dashboard'),
            default           => redirect('/'),
        };
    }

    // ────────────────────────────────────────────────────────────────────
    // Profile
    // ────────────────────────────────────────────────────────────────────
    public function showProfile(): View
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    // ✅ FIXED: Update Profile (NO MORE INTELLISENSE ERROR!)
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'nip' => ['sometimes', 'nullable', 'string', 'max:30'],
            'nomor_telepon' => ['sometimes', 'nullable', 'string', 'max:20'],
            'jabatan' => ['sometimes', 'nullable', 'string', 'max:255'],
            'unit_kerja_id' => ['sometimes', 'nullable', 'exists:unit_kerjas,id'],
            'signature' => ['sometimes', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'signature.image' => 'File harus gambar.',
            'signature.max' => 'Ukuran maksimal 2MB.',
        ]);

        // Update fields
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('nip')) {
            $user->nip = $request->nip;
        }
        if ($request->filled('nomor_telepon')) {
            $user->nomor_telepon = $request->nomor_telepon;
        }
        if ($request->filled('jabatan')) {
            $user->jabatan = $request->jabatan;
        }
        if ($request->has('unit_kerja_id')) {
            $user->unit_kerja_id = $request->unit_kerja_id;
        }    
        // Handle signature upload
       if ($request->hasFile('signature')) {
        // Hapus signature lama jika ada
        if ($user->signature && Storage::disk('public')->exists($user->signature)) {
            Storage::disk('public')->delete($user->signature);
        }
        
        // Simpan signature baru
        $path = $request->file('signature')->store('signatures', 'public');
        
        // Simpan path ke kolom 'signature' sesuai database kamu
        $user->signature = $path; 
        $user->save();
    }

        $user->save();

        return back()->with('success', '✅ Profile berhasil diupdate!');
    }

    // ✅ FIXED: Change Password (NO MORE INTELLISENSE ERROR!)
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        // ✅ SAFE ASSIGNMENT
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', '✅ Password berhasil diubah!');
    }

    // ────────────────────────────────────────────────────────────────────
    // Register (Superadmin only)
    // ────────────────────────────────────────────────────────────────────
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['superadmin','kepalabpmp','kasubag','adminpersediaan','adminsarpras','adminasettetap','pegawai'])],
            'nip' => ['nullable', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit_kerja_id' => ['nullable', 'exists:unit_kerjas,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'unit_kerja_id' => $request->unit_kerja_id,
            'is_active' => true,
        ]);

        return redirect()->route('login')
            ->with('success', "✅ User {$user->name} berhasil dibuat!");
    }

    // ────────────────────────────────────────────────────────────────────
    // Update Tanda Tangan (File & Canvas)
    // ────────────────────────────────────────────────────────────────────
    public function updateSignature(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'signature' => ['nullable', 'required_without:signature_base64', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'signature_base64' => ['nullable', 'required_without:signature', 'string', 'regex:/^data:image\/(png|jpeg);base64,/'],
        ]);

        $newPath = null;

        if ($request->hasFile('signature')) {
            $newPath = $request->file('signature')->store('signatures', 'public');
        } else {
            [$metadata, $payload] = explode(';base64,', $request->string('signature_base64')->toString(), 2);
            $binary = base64_decode($payload, true);

            if ($binary === false || strlen($binary) > 2 * 1024 * 1024 || getimagesizefromstring($binary) === false) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'signature_base64' => 'Data tanda tangan tidak valid atau melebihi 2 MB.',
                ]);
            }

            $extension = str_ends_with($metadata, 'jpeg') ? 'jpg' : 'png';
            $newPath = 'signatures/ttd_'.$user->id.'_'.now()->format('YmdHisv').'.'.$extension;

            if (! Storage::disk('public')->put($newPath, $binary)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'signature_base64' => 'Tanda tangan gagal disimpan.',
                ]);
            }
        }

        $oldPath = $user->signature;
        $user->signature = $newPath;
        $user->save();

        if ($oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('success', 'Tanda tangan berhasil diperbarui!');
    }
}
