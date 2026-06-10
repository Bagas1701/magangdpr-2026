<x-filament-panels::page.simple>
    <div>
        {{ $this->form }}

        <x-filament::button
            type="submit"
            form="authenticate"
            class="w-full mt-6"
        >
            Sign in
        </x-filament::button>
    </div>
</x-filament-panels::page.simple>