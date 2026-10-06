<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne; 

use Illuminate\Database\Eloquent\Model;
use App\Models\UserProfile;
use App\Models\PeminjamanGedung;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use App\Models\UnitKerja;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'nomor_telepon',
        'password',
        'role',
        'nip',
        'jabatan',
        'is_active',
        'signature',
        'unit_kerja_id'
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /**
     * Relasi ke profil user (1:1)
     */
    // public function profile(): HasOne
    // {
    //     return $this->hasOne(UserProfile::class);
    // }

    // /**
    //  * Profile completeness percentage
    //  */
    // public function getProfileCompletenessAttribute(): int
    // {
    //     return $this->profile?->profile_completeness ?? 0;
    // }

    // SCOPE
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ----------------------------------------------------------------
    // Helper: pengecekan peran
    // ----------------------------------------------------------------

    /** Apakah user adalah Super Admin? */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /** Apakah user adalah Kepala BPMP? */
    public function isKepalaBPMP(): bool
    {
        return $this->role === 'kepalabpmp';
    }

    /** Apakah user adalah Kasubag TU? */
    public function isKasubag(): bool
    {
        return $this->role === 'kasubag';
    }

    /** Apakah user adalah Admin Persediaan? */
    public function isAdminPersediaan(): bool
    {
        return $this->role === 'adminpersediaan';
    }

    /** Apakah user adalah Admin Sarpras? */
    public function isAdminSarpras(): bool
    {
        return $this->role === 'adminsarpras';
    }

    /** Apakah user adalah Admin Aset Tetap? */
    public function isAdminAsetTetap(): bool
    {
        return $this->role === 'adminasettetap';
    }

    /** Apakah user adalah Pegawai biasa? */
    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }

    /**
     * Cek apakah user memiliki salah satu dari peran yang diberikan.
     *
     * @param  string|array<string>  $roles
     */
    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles, true);
    }

    /**
     * Label peran yang ramah-baca untuk ditampilkan di UI.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin'      => 'Super Admin',
            'kepalabpmp'      => 'Kepala BPMP',
            'kasubag'         => 'Kasubag TU',
            'adminpersediaan' => 'Admin Persediaan',
            'adminsarpras'    => 'Admin Sarana Prasarana',
            'adminasettetap'  => 'Admin Aset Tetap',
            'pegawai'         => 'Pegawai',
            default           => ucfirst($this->role),
        };
    }

    // Di model User
    public function peminjamanGedung()
    {
        return $this->hasMany(PeminjamanGedung::class, 'user_id');
    }
    /**
     * Relasi ke tabel unit_kerjas (Many to One)
     * Satu user/pegawai hanya memiliki satu unit kerja.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
}
