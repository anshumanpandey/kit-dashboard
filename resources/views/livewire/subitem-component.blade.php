<div class="p-6">
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create Subitem') }}
		</x-jet-button>
	</div>
	
    {{-- The data table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <livewire:subitem-table per-page="10" />
				</div>
			</div>
		</div>
	</div>
	
	
    {{-- Modal Form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible" id="formModal">
        <x-slot name="title">
            {{ __('Create or Update Sub Items') }}
		</x-slot>
		
        <x-slot name="content">
			
            <select wire:model="item_id" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <option value="">-- Select a Item--</option>    
                @foreach (App\Models\Item::getlinkeditems() as $key => $value)
                <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>    
                @endforeach
			</select>
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Serial no') }}" />
                <x-jet-input wire:model="serialnumber" id="" class="block mt-1 w-full" type="text" />
                @error('serialnumber') <span class="error">{{ $message }}</span> @enderror
			</div>  
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Picture') }}" />
                <x-jet-input wire:model="pictureurl" id="" class="block mt-1 w-full" type="file" />
                @error('pictureurl') <span class="error">{{ $message }}</span> @enderror
			</div>  
			
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Date of purchase') }}" />
                <x-jet-input wire:model="date_of_purchase" id="" class="block mt-1 w-full" type="date" />
                @error('date_of_purchase') <span class="error">{{ $message }}</span> @enderror
			</div> 
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Warranty Expiry Period') }}" />
                <x-jet-input wire:model="warranty_expiry_period" id="" class="block mt-1 w-full" type="date" />
                @error('warranty_expiry_period') <span class="error">{{ $message }}</span> @enderror
			</div> 
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Quantity') }}" />
                <x-jet-input wire:model="quantity" id="" class="block mt-1 w-full" type="number" />
                @error('quantity') <span class="error">{{ $message }}</span> @enderror
			</div> 
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Receipt') }}" />
                <x-jet-input wire:model="receipt_url" id="" class="block mt-1 w-full" type="file" />
                @error('receipt_url') <span class="error">{{ $message }}</span> @enderror
			</div> 
			
            <div class="mt-4">
                <select wire:model="condition" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select Condition --</option>    
                    @foreach (App\Models\Subitem::getConditions() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>    
                    @endforeach
				</select> 
                @error('condition') <span class="error">{{ $message }}</span> @enderror
			</div>
			
            <div class="mt-4">
                <select wire:model="status" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select Status --</option>    
                    @foreach (App\Models\Subitem::getStatus() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>    
                    @endforeach
				</select> 
                @error('status') <span class="error">{{ $message }}</span> @enderror
			</div>
			
			
            <div class="mt-4" wire:ignore>
                <select wire:model="linkedsubitems" id="" multiple="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500 form-select-engineerSearch">
                    <option value="">-- Select Linked Sub Items--</option>    
                    @foreach (App\Models\Subitem::getlinkedsubitems() as $key => $value)
                    <option value="{{ $value['id'] }}">{{ $value['serialnumber'] }}</option>    
                    @endforeach
				</select>
				
				
			</div>
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Note') }}" />
                <x-jet-input wire:model="notes" id="" class="block mt-1 w-full" type="textarea" />
                @error('notes') <span class="error">{{ $message }}</span> @enderror
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
			{{ __('Delete SubItem') }}
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
		@this.set('linkedsubitems', data);
	});
</script>
@endpush
