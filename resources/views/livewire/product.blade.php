<div class="m-3 text-right">
    <x-filament::modal id="product-faq" slide-over width="2xl">
        <x-slot name="trigger">
            <x-filament::button style="color: #000; background: #b6d7ff;">
                Create New FAQ
            </x-filament::button>
        </x-slot>

        {{ $this->form }}

        <x-slot name="footerActions">
            <x-filament::button wire:click="create()" style="color: #000; background: #b6d7ff;">
                Update </x-filament::button>
        </x-slot>
    </x-filament::modal>


    <div class="mt-3">
        {{ $this->table }}
    </div>

</div>