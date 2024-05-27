<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col items-center justify-center gap-7 py-20 md:px-4">
            {{-- Heading --}}
            <div class="w-full flex flex-col gap-4 pb-20">
                {{-- Title --}}
                <span class="text-3xl">Sripuja Elektronik</span>
                {{-- Details --}}
                <div class="flex flex-col gap-4">
                    {{-- Tanggal --}}
                    <div class="px-6 flex items-center gap-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            class="fill-gray-700 dark:fill-white">
                            <path
                                d="M21 20V6c0-1.103-.897-2-2-2h-2V2h-2v2H9V2H7v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2zM9 18H7v-2h2v2zm0-4H7v-2h2v2zm4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm2-5H5V7h14v2z">
                            </path>
                        </svg>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-700 dark:text-gray-400">Tanggal</span>
                            <span>{{ now()->translatedFormat('l, d M Y') }}</span>
                        </div>
                    </div>
                    {{-- Waktu --}}
                    <div class="px-6 flex items-center gap-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            class="fill-gray-700 dark:fill-white">
                            <path
                                d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm3.293 14.707L11 12.414V6h2v5.586l3.707 3.707-1.414 1.414z">
                            </path>
                        </svg>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-700 dark:text-gray-400">Batas Waktu</span>
                            <span>{{ $batas_waktu_masuk }} WITA</span>
                        </div>
                    </div>
                    {{-- Check-In Indicator --}}
                    <div class="pt-4 px-6 flex gap-5 items-center">
                        {{-- If user has checked-in --}}
                        @if (isset($record))
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                class="fill-green-500">
                                <path
                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z">
                                </path>
                            </svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-700 dark:text-gray-400">Status Kehadiran</span>
                                <div class="flex gap-1">
                                    @if ('12:00:00' > date('H:i:s', strtotime($record->tanggal_waktu)))
                                        <span class="text-green-500">Tepat Waktu</span>
                                    @else
                                        <span class="text-red-500">Terlambat</span>
                                    @endif
                                    <span>- {{ date('H:i:s', strtotime($record->tanggal_waktu)) }} WITA</span>
                                </div>
                            </div>
                            {{-- User hasn't checked-in --}}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                class="fill-red-500">
                                <path
                                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm4.207 12.793-1.414 1.414L12 13.414l-2.793 2.793-1.414-1.414L10.586 12 7.793 9.207l1.414-1.414L12 10.586l2.793-2.793 1.414 1.414L13.414 12l2.793 2.793z">
                                </path>
                            </svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-700 dark:text-gray-400">Status Kehadiran</span>
                                <span>Belum Absen</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Check-In Button --}}
            <form wire:submit="checkIn" class="w-full flex justify-center">
                <button type="submit"
                    @if (isset($record)) class="btn text-white w-full bg-rose-500 filter brightness-75 py-3 rounded-xl" disabled
                @else class="btn w-full text-white bg-rose-500 py-3 rounded-xl" @endif>Check-In</button>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
