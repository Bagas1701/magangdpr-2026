<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Timeline Aktivitas Aspirasi
        </x-slot>

        <x-slot name="description">
            Riwayat lengkap aktivitas aspirasi, meliputi status, lampiran, kajian, dan keputusan anggota dewan.
        </x-slot>

        <div class="relative space-y-6">
            @forelse ($this->getActivities() as $activity)
                @php
                    $badge = match ($activity['type']) {
                        'create' => [
                            'label' => 'Dibuat',
                            'icon' => 'heroicon-o-document-plus',
                            'color' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                        ],
                        'status' => [
                            'label' => 'Status',
                            'icon' => 'heroicon-o-arrow-path',
                            'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200',
                        ],
                        'attachment' => [
                            'label' => 'Lampiran',
                            'icon' => 'heroicon-o-paper-clip',
                            'color' => 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-200',
                        ],
                        'note' => [
                            'label' => 'Kajian',
                            'icon' => 'heroicon-o-chat-bubble-left-right',
                            'color' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-200',
                        ],
                        'approval' => [
                            'label' => 'Approval',
                            'icon' => match (true) {
                                str_contains(strtolower($activity['title']), 'ditolak') => 'heroicon-o-x-circle',
                                str_contains(strtolower($activity['title']), 'revisi') => 'heroicon-o-arrow-uturn-left',
                                default => 'heroicon-o-check-badge',
                            },
                            'color' => match (true) {
                                str_contains(strtolower($activity['title']), 'ditolak') => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200',
                                str_contains(strtolower($activity['title']), 'revisi') => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200',
                                default => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200',
                            },
                        ],
                        default => [
                            'label' => 'Aktivitas',
                            'icon' => 'heroicon-o-clock',
                            'color' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                        ],
                    };
                @endphp

                <div class="relative flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full border bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                            @svg($badge['icon'], 'h-5 w-5 text-gray-600 dark:text-gray-300')
                        </div>

                        @if (! $loop->last)
                            <div class="mt-2 h-full min-h-10 w-px bg-gray-200 dark:bg-gray-700"></div>
                        @endif
                    </div>

                    <div class="flex-1 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $badge['color'] }}">
                                        {{ $badge['label'] }}
                                    </span>

                                    <h3 class="font-semibold text-gray-950 dark:text-white">
                                        {{ $activity['title'] }}
                                    </h3>
                                </div>

                                <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                                    {{ $activity['description'] }}
                                </p>

                                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>
                                        Oleh:
                                        <span class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ $activity['actor'] }}
                                        </span>
                                    </span>

                                    <span class="hidden sm:inline">•</span>

                                    <span>
                                        {{ optional($activity['time'])->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <div class="shrink-0 rounded-lg bg-gray-50 px-3 py-2 text-right text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                {{ optional($activity['time'])->format('d M Y') }}
                                <br>
                                {{ optional($activity['time'])->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500 dark:border-gray-700">
                    Belum ada aktivitas.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>