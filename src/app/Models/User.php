<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
// use Filament\Facades\Filament;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'avatar_url',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole([
            'super_admin',
            'admin',
            'anggota_dewan',
            'tenaga_ahli',
            'staf',
        ]);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isAdminLevel(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin']);
    }

    public function canManageUsers(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function canManageMasterData(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin']);
    }

    public function canAccessAspirasiModule(): bool
    {
        return $this->hasAnyRole([
            'super_admin',
            'admin',
            'anggota_dewan',
            'tenaga_ahli',
            'staf',
        ]);
    }

    public function canApproveAspirasi(): bool
    {
        return $this->hasAnyRole([
            'super_admin',
            'admin',
            'anggota_dewan',
        ]);
    }

   public function sendPasswordResetNotification($token): void
    {
        ResetPassword::createUrlUsing(function () use ($token) {
            return URL::temporarySignedRoute(
                'filament.admin.auth.password-reset.reset',
                now()->addMinutes(config('auth.passwords.users.expire', 60)),
                [
                    'email' => $this->email,
                    'token' => $token,
                ]
            );
        });

        $this->notify(new ResetPassword($token));
    }
}