<?php

namespace App\Services;

use App\Models\Aspirasi;
use App\Models\User;
use Filament\Notifications\Notification;

class AspirasiNotificationService
{
    public static function notifyRole(array|string $roles, string $title, string $body): void
    {
        $users = User::role($roles)->get();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            Notification::make()
                ->title($title)
                ->body($body)
                ->icon('heroicon-o-bell-alert')
                ->success()
                ->sendToDatabase($user);
        }
    }

    public static function aspirasiMasuk(Aspirasi $aspirasi): void
    {
        self::notifyRole(
            ['super_admin', 'admin'],
            'Aspirasi baru masuk',
            self::label($aspirasi) . ' perlu ditinjau.'
        );
    }

    public static function menungguTindakLanjut(Aspirasi $aspirasi): void
    {
        self::notifyRole(
            ['tenaga_ahli', 'admin', 'super_admin'],
            'Aspirasi siap ditindaklanjuti',
            self::label($aspirasi) . ' sudah diverifikasi dan masuk tahap tindak lanjut.'
        );
    }

    public static function menungguPersetujuan(Aspirasi $aspirasi): void
    {
        self::notifyRole(
            ['anggota_dewan', 'admin', 'super_admin'],
            'Aspirasi menunggu persetujuan',
            self::label($aspirasi) . ' menunggu keputusan Anggota Dewan.'
        );
    }

    public static function keputusanDewan(Aspirasi $aspirasi, string $keputusan): void
    {
        self::notifyRole(
            ['staf', 'tenaga_ahli', 'admin', 'super_admin'],
            'Keputusan aspirasi diperbarui',
            self::label($aspirasi) . ' telah ' . $keputusan . '.'
        );
    }

    public static function aspirasiDihapus(Aspirasi $aspirasi): void
    {
        self::notifyRole(
            ['super_admin', 'admin'],
            'Aspirasi dihapus',
            self::label($aspirasi) . ' telah dihapus.'
        );
    }

    public static function aspirasiDihapusByData(string $judul, string $nomorTiket = '-'): void
    {
        self::notifyRole(
            ['super_admin', 'admin'],
            'Aspirasi dihapus',
            "Aspirasi {$judul} dengan nomor tiket {$nomorTiket} telah dihapus."
        );
    }

    private static function label(Aspirasi $aspirasi): string
    {
        return $aspirasi->nomor_tiket
            ? "Aspirasi {$aspirasi->nomor_tiket}"
            : "Aspirasi {$aspirasi->judul}";
    }
}
