<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col items-center justify-center gap-7 py-20">
            {{-- Heading --}}
            <div class="w-full flex flex-col gap-3 pb-24">
                {{-- Title --}}
                <span class="text-3xl">Sripuja Elektronik</span>
                {{-- Details --}}
                <div class="px-6 flex flex-row items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                        class="fill-white">
                        <path
                            d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm3.293 14.707L11 12.414V6h2v5.586l3.707 3.707-1.414 1.414z">
                        </path>
                    </svg>
                    <div class="flex flex-col">
                        <span class="text-xs">Batas Waktu</span>
                        <span>{{ $batas_waktu_masuk }} GMT+8</span>
                    </div>
                </div>
            </div>
            {{-- Check-In Button --}}
            <form wire:submit="checkIn" class="w-full flex justify-center">
                <button type="submit" @if (isset($record)) class="btn w-full bg-rose-500 filter brightness-75 py-3 rounded-xl" disabled
                @else class="btn w-full bg-rose-500 py-3 rounded-xl"  @endif>Check-In</button>
            </form>
            {{-- Check-In Indicator --}}
            <div class="flex flex-row gap-4 items-center justify-center">
                {{-- If user has checked-in --}}
                @if (isset($record))
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                        class="fill-green-500">
                        <path
                            d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.999 14.413-3.713-3.705L7.7 11.292l2.299 2.295 5.294-5.294 1.414 1.414-6.706 6.706z">
                        </path>
                    </svg>
                    <div class="flex flex-col">
                        <span class="text-xs">Absen</span>
                        <span>{{ date('H:i:s', strtotime($record->tanggal_waktu)) }}</span>
                    </div>
                {{-- User hasn't checked-in --}}
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                        class="fill-red-500">
                        <path
                            d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm4.207 12.793-1.414 1.414L12 13.414l-2.793 2.793-1.414-1.414L10.586 12 7.793 9.207l1.414-1.414L12 10.586l2.793-2.793 1.414 1.414L13.414 12l2.793 2.793z">
                        </path>
                    </svg>
                    <span>Absensi Hari Ini</span>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
