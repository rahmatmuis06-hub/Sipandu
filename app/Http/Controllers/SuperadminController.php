<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssetTetap;
use App\Models\Persediaan;
use App\Models\BarangLainnya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SuperadminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        $countAset = Schema::hasTable('aset_tetap') ? AssetTetap::count() : 0;
        $countPersediaan = Schema::hasTable('persediaan') ? Persediaan::where('kode_kategori', '!=', 'BLN')->count() : 0;
        $countBarangLainnya = Schema::hasTable('barang_lainnya') ? BarangLainnya::count() : 0;

        return view('superadmin.dashbord', compact(
            'totalUsers', 'activeUsers', 'inactiveUsers',
            'countAset', 'countPersediaan', 'countBarangLainnya'
        ));
    }

    public function manajemenUser(Request $request) // <-- Pastikan nama fungsi sesuai dengan rute di web.php kamu
    {
        // 1. AMBIL DATA USERS DARI DATABASE
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%")
                  ->orWhere('nip', 'like', "%{$request->search}%");
        }

        // INI DIA VARIABEL $users YANG DICARI OLEH BLADE!
        $users = $query->orderBy('role', 'asc')->paginate(10)->withQueryString();

        // 2. AMBIL DATA LOG AKTIVITAS
        $logAktivitas = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select('users.name', 'users.role', 'sessions.ip_address', 'sessions.last_activity', 'sessions.user_agent')
            ->orderBy('sessions.last_activity', 'desc')
            ->get()
            ->map(function ($log) {
                $log->last_activity_format = \Carbon\Carbon::createFromTimestamp($log->last_activity)->diffForHumans();
                return $log;
            });

        // 3. KIRIM VARIABEL KE VIEW
        // Pastikan nama view ('superadmin.manajemen_user') sesuai dengan nama file blade kamu!
        return view('superadmin.manajemen_user', compact('users', 'logAktivitas'));
    }

    public function storePengguna(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:superadmin,kepalabpmp,kasubag,adminpersediaan,adminsarpras,adminasettetap,pegawai',
            'nip'      => 'nullable|string|max:30',
            'jabatan'  => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'nip'      => $request->nip,
            'jabatan'  => $request->jabatan,
            'is_active'=> true, // Secara default user baru langsung aktif
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function updatePengguna(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'    => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:superadmin,kepalabpmp,kasubag,adminpersediaan,adminsarpras,adminasettetap,pegawai',
            'nip'      => 'nullable|string|max:30',
            'jabatan'  => 'nullable|string|max:255',
        ]);

        $updateData = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
            'nip'      => $request->nip,
            'jabatan'  => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroyPengguna(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    // Fungsi Baru: Untuk Switch Aktif / Nonaktif
    public function toggleStatus(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri!');
        }

        $user->update([
            'is_active' => !$user->is_active // Membalikkan status (true jadi false, false jadi true)
        ]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$statusText}!");
    }

    /**
     * ⚡ PUSAT KELOLA & BACKUP DATA (ASET, PERSEDIAAN, BARANG LAINNYA)
     */
    public function kelolaData()
    {
        $stats = [
            'aset' => [
                'total_item'       => Schema::hasTable('aset_tetap') ? AssetTetap::count() : 0,
                'total_nilai'      => Schema::hasTable('aset_tetap') ? (float) AssetTetap::sum('nilai_perolehan') : 0,
                'transaksi_masuk'  => Schema::hasTable('transaksi_masuk_aset_tetap') ? DB::table('transaksi_masuk_aset_tetap')->count() : 0,
                'transaksi_keluar' => Schema::hasTable('transaksi_keluar_aset_tetap') ? DB::table('transaksi_keluar_aset_tetap')->count() : 0,
                'mutasi'           => Schema::hasTable('mutasi_barang') ? DB::table('mutasi_barang')->count() : 0,
            ],
            'persediaan' => [
                'total_item'       => Schema::hasTable('persediaan') ? Persediaan::where('kode_kategori', '!=', 'BLN')->count() : 0,
                'total_unit'       => Schema::hasTable('persediaan') ? (int) Persediaan::where('kode_kategori', '!=', 'BLN')->sum('jumlah') : 0,
                'total_nilai'      => Schema::hasTable('persediaan') ? (float) Persediaan::where('kode_kategori', '!=', 'BLN')->sum('harga_total') : 0,
                'transaksi_masuk'  => Schema::hasTable('transaksi_masuk_persediaan') ? DB::table('transaksi_masuk_persediaan')->count() : 0,
                'transaksi_keluar' => Schema::hasTable('transaksi_keluar_persediaan') ? DB::table('transaksi_keluar_persediaan')->count() : 0,
                'permintaan'       => Schema::hasTable('permintaan_persediaan') ? DB::table('permintaan_persediaan')->count() : 0,
            ],
            'barang_lainnya' => [
                'total_item'       => Schema::hasTable('barang_lainnya') ? BarangLainnya::count() : 0,
                'total_unit'       => Schema::hasTable('barang_lainnya') ? (int) BarangLainnya::sum('jumlah') : 0,
                'total_nilai'      => Schema::hasTable('barang_lainnya') ? (float) BarangLainnya::sum('harga_total') : 0,
                'peminjaman'       => Schema::hasTable('peminjaman_barang_lainnya') ? DB::table('peminjaman_barang_lainnya')->count() : 0,
            ],
        ];

        return view('superadmin.kelola_data', compact('stats'));
    }

    /**
     * ⚡ BACKUP DATA / DATABASE (.SQL ATAU .JSON)
     */
    public function backupData(Request $request)
    {
        $kategori = $request->input('kategori', 'full');
        $format = $request->input('format', 'sql');
        $timestamp = now()->format('Y-m-d_His');

        $tablesMap = [
            'aset' => [
                'aset_tetap',
                'transaksi_masuk_aset_tetap',
                'transaksi_keluar_aset_tetap',
                'mutasi_barang',
                'ajuan_mutasi',
                'peminjaman_barang',
                'detail_peminjaman_barang',
                'pengembalian_barang',
            ],
            'persediaan' => [
                'persediaan',
                'transaksi_masuk_persediaan',
                'transaksi_keluar_persediaan',
                'permintaan_persediaan',
                'detail_permintaan_persediaan',
                'stok_opname',
                'stok_opname_detail',
            ],
            'barang_lainnya' => [
                'barang_lainnya',
                'peminjaman_barang_lainnya',
            ],
        ];

        if ($kategori === 'full') {
            $filename = "sipandu_backup_database_penuh_{$timestamp}.sql";
            return $this->streamSqlDump([], $filename);
        }

        if (!isset($tablesMap[$kategori])) {
            return back()->with('error', 'Kategori backup tidak valid.');
        }

        $tables = $tablesMap[$kategori];

        if ($format === 'json') {
            $data = [];
            foreach ($tables as $t) {
                if (Schema::hasTable($t)) {
                    $data[$t] = DB::table($t)->get();
                }
            }
            $filename = "sipandu_backup_{$kategori}_{$timestamp}.json";
            return response()->streamDownload(function () use ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }, $filename, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        $filename = "sipandu_backup_{$kategori}_{$timestamp}.sql";
        return $this->streamSqlDump($tables, $filename);
    }

    /**
     * ⚡ HAPUS / RESET DATA (ASET, PERSEDIAAN, BARANG LAINNYA)
     */
    public function hapusData(Request $request)
    {
        $request->validate([
            'kategori'          => 'required|in:aset,persediaan,barang_lainnya,all',
            'konfirmasi'        => 'required|string',
            'password_operator' => 'required|string',
        ], [
            'kategori.required'          => 'Kategori data yang akan dihapus wajib dipilih.',
            'konfirmasi.required'        => 'Tuliskan kata konfirmasi untuk melanjutkan.',
            'password_operator.required' => 'Password operator wajib diisi untuk verifikasi keamanan.',
        ]);

        // Verifikasi konfirmasi teks (HARUS "HAPUS DATA")
        if (trim(strtoupper($request->konfirmasi)) !== 'HAPUS DATA') {
            return back()->with('error', 'Kata konfirmasi salah! Anda harus mengetik persis "HAPUS DATA" untuk melanjutkan.');
        }

        // Verifikasi password operator saat ini
        if (!Hash::check($request->password_operator, Auth::user()->password)) {
            return back()->with('error', 'Password operator salah! Tindakan penghapusan dibatalkan demi keamanan data.');
        }

        $kategori = $request->kategori;
        $includeRelations = $request->boolean('include_relations', true);
        $summary = [];

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

            // 1. HAPUS DATA ASET TETAP
            if ($kategori === 'aset' || $kategori === 'all') {
                $countAset = 0;
                if (Schema::hasTable('aset_tetap')) {
                    $countAset = DB::table('aset_tetap')->count();
                    DB::table('aset_tetap')->truncate();
                }

                if ($includeRelations) {
                    $relTables = [
                        'transaksi_masuk_aset_tetap',
                        'transaksi_keluar_aset_tetap',
                        'mutasi_barang',
                        'ajuan_mutasi',
                        'detail_peminjaman_barang',
                        'peminjaman_barang',
                        'pengembalian_barang',
                    ];
                    foreach ($relTables as $rt) {
                        if (Schema::hasTable($rt)) {
                            DB::table($rt)->truncate();
                        }
                    }
                }
                $summary[] = "Data Aset Tetap ({$countAset} item)" . ($includeRelations ? " beserta seluruh riwayat transaksi & mutasi" : "");
            }

            // 2. HAPUS DATA PERSEDIAAN
            if ($kategori === 'persediaan' || $kategori === 'all') {
                $countPersediaan = 0;
                if (Schema::hasTable('persediaan')) {
                    $countPersediaan = DB::table('persediaan')->count();
                    DB::table('persediaan')->truncate();
                }

                if ($includeRelations) {
                    $relTables = [
                        'transaksi_masuk_persediaan',
                        'transaksi_keluar_persediaan',
                        'detail_permintaan_persediaan',
                        'permintaan_persediaan',
                        'stok_opname_detail',
                        'stok_opname',
                    ];
                    foreach ($relTables as $rt) {
                        if (Schema::hasTable($rt)) {
                            DB::table($rt)->truncate();
                        }
                    }
                }
                $summary[] = "Data Persediaan ({$countPersediaan} item)" . ($includeRelations ? " beserta seluruh riwayat transaksi & permintaan persediaan" : "");
            }

            // 3. HAPUS DATA BARANG LAINNYA
            if ($kategori === 'barang_lainnya' || $kategori === 'all') {
                $countLainnya = 0;
                if (Schema::hasTable('barang_lainnya')) {
                    $countLainnya = DB::table('barang_lainnya')->count();
                    DB::table('barang_lainnya')->truncate();
                }
                if (Schema::hasTable('peminjaman_barang_lainnya')) {
                    DB::table('peminjaman_barang_lainnya')->truncate();
                }
                $summary[] = "Data Barang Lainnya ({$countLainnya} item) beserta permohonan peminjaman";
            }

            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

            Log::warning("Pembersihan Data Berhasil dieksekusi oleh Operator: " . Auth::user()->name . " (" . Auth::user()->username . ") pada kategori: {$kategori}");

            return back()->with('success', 'Berhasil! ' . implode(' dan ', $summary) . ' telah berhasil dihapus / dikosongkan.');

        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            Log::error("Gagal Hapus Data Inventaris: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * ⚡ STREAM SQL DUMP GENERATOR (PURE PHP + PDO)
     */
    private function streamSqlDump(array $targetTables = [], string $filename = 'backup.sql')
    {
        return response()->streamDownload(function () use ($targetTables) {
            $pdo = DB::connection()->getPdo();

            // Set buffered output and time limit
            set_time_limit(300);
            if (ob_get_level()) {
                ob_end_clean();
            }

            echo "-- =====================================================\n";
            echo "-- SIPANDU BPMP PROVINSI GORONTALO - DATABASE BACKUP\n";
            echo "-- Tanggal & Waktu: " . now()->format('Y-m-d H:i:s') . "\n";
            echo "-- Dibuat oleh Operator / Super Admin: " . (auth()->user()->name ?? 'Operator') . "\n";
            echo "-- =====================================================\n\n";
            echo "SET FOREIGN_KEY_CHECKS = 0;\n";
            echo "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
            echo "START TRANSACTION;\n";
            echo "SET time_zone = '+00:00';\n\n";

            if (empty($targetTables)) {
                $tables = [];
                $stmt = $pdo->query('SHOW FULL TABLES WHERE Table_Type = "BASE TABLE"');
                while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = $targetTables;
            }

            foreach ($tables as $table) {
                if (!Schema::hasTable($table)) {
                    continue;
                }

                echo "-- -----------------------------------------------------\n";
                echo "-- Struktur dan Data Tabel: `{$table}`\n";
                echo "-- -----------------------------------------------------\n";
                echo "DROP TABLE IF EXISTS `{$table}`;\n";

                $createTableStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $createTableRow = $createTableStmt->fetch(\PDO::FETCH_NUM);
                if ($createTableRow && isset($createTableRow[1])) {
                    echo $createTableRow[1] . ";\n\n";
                }

                // Dump data
                $rowsStmt = $pdo->query("SELECT * FROM `{$table}`");
                $rows = [];
                $batchSize = 50;

                while ($row = $rowsStmt->fetch(\PDO::FETCH_ASSOC)) {
                    $columns = array_map(function ($col) {
                        return "`" . str_replace("`", "``", $col) . "`";
                    }, array_keys($row));

                    $values = array_map(function ($val) use ($pdo) {
                        if (is_null($val)) return "NULL";
                        return $pdo->quote($val);
                    }, array_values($row));

                    $rows[] = "(" . implode(", ", $values) . ")";

                    if (count($rows) >= $batchSize) {
                        echo "INSERT INTO `{$table}` (" . implode(", ", $columns) . ") VALUES\n" . implode(",\n", $rows) . ";\n";
                        $rows = [];
                    }
                }

                if (!empty($rows)) {
                    echo "INSERT INTO `{$table}` (" . implode(", ", $columns) . ") VALUES\n" . implode(",\n", $rows) . ";\n\n";
                } else {
                    echo "\n";
                }
            }

            echo "SET FOREIGN_KEY_CHECKS = 1;\n";
            echo "COMMIT;\n";
            echo "-- Akhir dari file backup SQL\n";
        }, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}