<div class="p-6">
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create Item') }}
		</x-jet-button>
	</div>
	
    {{-- The data table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <livewire:items-table per-page="10" />
				</div>
			</div>
		</div>
	</div>
	
    <div class="mt-5">
        {{ $data->links() }}
	</div>
	
    {{-- Modal Form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Create or Update Item') }}
		</x-slot>
		
        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Item name') }}" />
                <x-jet-input wire:model="name" id="" class="block mt-1 w-full" type="text" />
                @error('name') <span class="error">{{ $message }}</span> @enderror
			</div>  
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Type') }}" />
				
                <select wire:model="item_type_id" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select a Item type --</option>
                    @foreach (App\Models\ItemType::getItemTypes() as $key => $value)
                    <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                    @endforeach
				</select>
				
                @error('item_type_id') <span class="error">{{ $message }}</span> @enderror
			</div>  
            @if($item_type_id == 1) 
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Cable length') }}" />
                <x-jet-input wire:model="cable_length" id="" class="block mt-1 w-full" type="text" />
                @error('cable_length') <span class="error">{{ $message }}</span> @enderror
			</div> 
            @else
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Make') }}" />
                <x-jet-input wire:model="make" id="" class="block mt-1 w-full" type="text" />
                @error('make') <span class="error">{{ $message }}</span> @enderror
			</div> 
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Model') }}" />
                <x-jet-input wire:model="model" id="" class="block mt-1 w-full" type="text" />
                @error('model') <span class="error">{{ $message }}</span> @enderror
			</div> 
            @endif
			
            <div class="mt-4" wire:ignore>
                <x-jet-label for="" value="{{ __('Linked Items') }}" />
				
                <select wire:model="linked_items" multiple class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500 form-select-engineerSearch">
                    <option value="">-- Select a Linked Items --</option>    
                    @foreach (App\Models\Item::getlinkeditems() as $key => $value)
                    <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>    
                    @endforeach
				</select>
				
                @error('linked_items') <span class="error">{{ $message }}</span> @enderror
			</div>
			
		</x-slot>
		
		<x-slot name="footer">
			<x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
				{{ __('Cancel') }}
			</x-jet-secondary-button>
			
			@if ($modelId)
			<x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
				{{ __('Update') }}
			</x-jet-danger-button>
			@else
			<x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
				{{ __('Create') }}
			</x-jet-danger-button>
			@endif
		</x-slot>
	</x-jet-dialog-modal>
	
	{{-- The Delete Modal --}}
	<x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
		<x-slot name="title">
			{{ __('Delete Item') }}
		</x-slot>
		
		<x-slot name="content">
			{{ __('Are you sure you want to delete this item?') }}
		</x-slot>
		
		<x-slot name="footer">
			<x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
				{{ __('Cancel') }}
			</x-jet-secondary-button>
			
			<x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
				{{ __('Confirm delete') }}
			</x-jet-danger-button>
		</x-slot>
	</x-jet-dialog-modal>
</div>

@push('scripts')
    <script>
        $('.form-select-engineerSearch').select2({width: '100%', sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),});
		$('.form-select-engineerSearch').on('change', function (e) {
			var data = $('.form-select-engineerSearch').select2("val");
			@this.set('linked_items', data);
		});
	</script>
@endpush