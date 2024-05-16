<div x-data="{ tab: 'penjualanChart' }">
    <br>
    <x-filament::tabs label="Content tabs" class="justify-center">
        <x-filament::tabs.item @click="tab = 'penjualanChart'" :alpine-active="'tab === \'penjualanChart\''">
            Penjualan
        </x-filament::tabs.item>

        <x-filament::tabs.item @click="tab = 'pembelianChart'" :alpine-active="'tab === \'pembelianChart\''">
            Pembelian
        </x-filament::tabs.item>

        <x-filament::tabs.item @click="tab = 'labaChart'" :alpine-active="'tab === \'labaChart\''">
            Laba
        </x-filament::tabs.item>
    </x-filament::tabs>

    <br>

    <div>
        <div x-show="tab === 'penjualanChart'">
            @livewire('filament.widgets.test-chart')
        </div>

        <div x-show="tab === 'pembelianChart'">
            @livewire('filament.widgets.beli-chart')
        </div>

        <div x-show="tab === 'labaChart'">
            @livewire('filament.widgets.laba-chart')
        </div>
    </div>
</div>
