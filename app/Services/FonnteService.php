<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Mengirim pesan WhatsApp via Fonnte API
     * * @param string $target Nomor HP tujuan (contoh: 08123456789)
     * @param string $message Isi pesan
     * @return bool
     */
    public static function sendMessage($target, $message)
    {
        // Notifikasi WhatsApp dinonaktifkan sepenuhnya
        return true;
    }
}
