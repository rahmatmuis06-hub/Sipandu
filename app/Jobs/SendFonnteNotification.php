<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;

class SendFonnteNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $target;
    public $message;

    /**
     * 🔥 1. JUMALAH MAKSIMAL PERCOBAAN (RETRY)
     * Jika gagal/timeout, Job ini akan dicoba ulang maksimal 3 kali.
     */
    public $tries = 3;

    /**
     * 🔥 2. BATAS WAKTU TIMEOUT (DALAM DETIK)
     * Jika dalam 30 detik Fonnte tidak merespons, anggap timeout dan matikan job untuk di-retry.
     */
    public $timeout = 30;

    public function __construct($target, $message)
    {
        $this->target = $target;
        $this->message = $message;
    }

    public function handle(): void
    {
        // Notifikasi WhatsApp dinonaktifkan sepenuhnya
        return;
    }
}
