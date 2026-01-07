<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <x-page-heading :title="__('Dashboard')" />

        <!-- Statistiek Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Aantal Afspraken -->
            <div class="overflow-hidden rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-500 shadow-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-white/90">
                                    {{ __('Aantal afspraken bekijken') }}
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-semibold text-white">
                                        {{ $aantalAfspraken }}
                                    </div>
                                </dd>
                                <dd class="mt-1 text-xs text-white/80">
                                    {{ __('Inzicht afspraken deze maand') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-600 px-6 py-3">
                    <a href="{{ route('afspraken.index') }}" class="flex items-center justify-between text-sm font-medium text-white hover:text-yellow-100">
                        <span>{{ __('Bekijk alle afspraken') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Omzet Bekijken -->
            <div class="overflow-hidden rounded-lg bg-gradient-to-br from-yellow-300 to-yellow-400 shadow-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-white/90">
                                    {{ __('Omzet bekijken') }}
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-semibold text-white">
                                        € {{ number_format($omzetMaand, 2, ',', '.') }}
                                    </div>
                                </dd>
                                <dd class="mt-1 text-xs text-white/80">
                                    {{ __('Omzet deze maand') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-500 px-6 py-3">
                    <a href="{{ route('factuur.index') }}" class="flex items-center justify-between text-sm font-medium text-white hover:text-yellow-100">
                        <span>{{ __('Bekijk alle facturen') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Inzicht Behandeling Bekijken -->
            <div class="overflow-hidden rounded-lg bg-gradient-to-br from-yellow-200 to-yellow-300 shadow-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-white/90">
                                    {{ __('Inzicht behandeling bekijken') }}
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-semibold text-white">
                                        {{ $aantalBehandelingen }}
                                    </div>
                                </dd>
                                <dd class="mt-1 text-xs text-white/80">
                                    {{ __('Behandelingen deze maand') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-400 px-6 py-3">
                    <div class="flex items-center justify-between text-sm font-medium text-white">
                        <span>{{ __('Totaal behandelingen') }}</span>
                        <span class="text-lg font-semibold">{{ $aantalBehandelingen }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jaaroverzicht -->
        <div class="rounded-lg bg-white p-6 shadow">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Jaaroverzicht') }} {{ date('Y') }}</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="border-l-4 border-blue-500 bg-blue-50 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-900">{{ __('Totale omzet dit jaar') }}</p>
                            <p class="text-2xl font-bold text-blue-600">€ {{ number_format($omzetJaar, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="border-l-4 border-green-500 bg-green-50 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-900">{{ __('Gemiddelde omzet per maand') }}</p>
                            <p class="text-2xl font-bold text-green-600">€ {{ number_format($omzetJaar / max(1, date('n')), 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
