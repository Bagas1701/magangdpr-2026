<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Page
{
    protected static ?string $navigationIcon = null;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'forgot-password';

    protected static string $view = 'filament.admin.pages.forgot-password';

    public ?array $data = [];

    public function mount(): void
    {
        if (auth()->check()) {
            redirect()->to('/admin');
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $status = Password::sendResetLink([
            'email' => $data['email'],
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            Notification::make()
                ->title('Link reset password berhasil dikirim.')
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('Email tidak ditemukan atau terlalu sering melakukan permintaan.')
            ->danger()
            ->send();
    }
}