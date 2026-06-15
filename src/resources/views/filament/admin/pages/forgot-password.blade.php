<x-filament-panels::page.simple>
    <div class="space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight">
                Lupa Password
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Masukkan email akun SIMALEX Anda. Link reset password akan dikirim ke email tersebut.
            </p>
        </div>

        <form wire:submit="send" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                Kirim Link Reset Password
            </x-filament::button>
        </form>

        <div class="text-center">
            <a href="{{ url('/admin/login') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-500">
                ← Kembali ke Login
            </a>
        </div>
    </div>
</x-filament-panels::page.simple>