<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait SafeSoftDeletes
{
    use SoftDeletes;

    /**
     * Cache per-PDO instance agar aman di production maupun RefreshDatabase saat testing.
     *
     * @var array<string, bool>
     */
    protected static array $verifiedSoftDeletePdos = [];

    protected static function bootSafeSoftDeletes(): void
    {
        static::ensureSoftDeleteColumns((new static())->getTable());
    }

    public static function ensureSoftDeleteColumns(string $table): void
    {
        try {
            $pdo = DB::connection()->getPdo();
            $cacheKey = spl_object_id($pdo) . ':' . $table;
            if (isset(self::$verifiedSoftDeletePdos[$cacheKey])) {
                return;
            }

            if (!Schema::hasTable($table)) {
                return;
            }

            $needDeletedAt = !Schema::hasColumn($table, 'deleted_at');
            $needDeletedBy = !Schema::hasColumn($table, 'deleted_by');

            if ($needDeletedAt || $needDeletedBy) {
                Schema::table($table, function (Blueprint $blueprint) use ($needDeletedAt, $needDeletedBy) {
                    if ($needDeletedAt) {
                        $blueprint->softDeletes();
                    }
                    if ($needDeletedBy) {
                        $blueprint->unsignedBigInteger('deleted_by')->nullable();
                    }
                });
            }

            self::$verifiedSoftDeletePdos[$cacheKey] = true;
        } catch (\Throwable $e) {
            Log::warning("SafeSoftDeletes::ensureSoftDeleteColumns({$table}): " . $e->getMessage());
        }
    }

    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
