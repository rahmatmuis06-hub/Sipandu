<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom soft deletes & pencatat penghapus pada tabel permintaan/peminjaman
     * agar data yang dibatalkan dapat dihapus dari tampilan Admin namun tetap terbackup di Super Admin.
     */
    public function up(): void
    {
        $tables = [
            'permintaan_persediaan',
            'peminjaman_kendaraan',
            'peminjaman_barang',
            'peminjaman_barang_lainnya',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->softDeletes();
                }
                if (!Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'permintaan_persediaan',
            'peminjaman_kendaraan',
            'peminjaman_barang',
            'peminjaman_barang_lainnya',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->dropColumn('deleted_by');
                }
                if (Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
