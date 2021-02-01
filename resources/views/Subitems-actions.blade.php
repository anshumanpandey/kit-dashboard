<div class="flex space-x-1 justify-around">
    
    <a wire:click="updateShowModal({{ $id }})" target="_blank" class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
	</a>
	
	<a wire:click="barcodeShowModal({{ $id }})" target="_blank" class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
        <i class="fa fa-barcode" aria-hidden="true"></i>
	</a>
	
    <button wire:click="deleteShowModal({{ $id }})" class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
	</button>
</div>


{{-- Modal Form --}}
<x-jet-dialog-modal wire:model="modalFormVisible" class="fixed inset-0 overflow-y-auto px-4 pt-6 sm:px-0">
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
			<x-jet-input wire:model="serialnumber" class="block mt-1 w-full" type="text" />
			@error('serialnumber') <span class="error">{{ $message }}</span> @enderror
		</div>  
		
		
		
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Picture') }}" />
			<x-jet-input wire:model="picture" id="" class="block mt-1 w-full" type="file" />
			@if(@$this->pictureurl)
			<a target="_blank" href="{{ asset('storage/photos/'.$this->pictureurl) }}">
				<img width="200" src="{{ asset('storage/photos/'.$this->pictureurl) }}" />
			</a>
			@endif
			@error('picture') <span class="error">{{ $message }}</span> @enderror
		</div>  
		
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Date of purchase') }}" />
			<x-jet-input wire:model="date_of_purchase" value="{{ $this->date_of_purchase ? $this->date_of_purchase : '' }}" id="" class="block mt-1 w-full" type="date" placeholder="dd-mm-yyyy"/>
			@error('date_of_purchase') <span class="error">{{ $message }}</span> @enderror
		</div> 
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Warranty Expiry Period') }}" />
			<x-jet-input wire:model="warranty_expiry_period" id="" class="block mt-1 w-full" type="date" value="{{ $this->warranty_expiry_period ? $this->warranty_expiry_period : '' }}"/>
				@error('warranty_expiry_period') <span class="error">{{ $message }}</span> @enderror
		</div>
		
		
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Quantity') }}" />
			<x-jet-input wire:model="quantity" id="" class="block mt-1 w-full" type="number" />
			@error('quantity') <span class="error">{{ $message }}</span> @enderror
		</div> 
		
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Receipt') }}" />
			<x-jet-input wire:model="receipt" id="" class="block mt-1 w-full" type="file" />
			@if(@$this->receipt_url)
			<a target="_blank" href="{{ asset('storage/photos/'.$this->receipt_url) }}">
				<img width="200" src="{{ asset('storage/photos/'.$this->receipt_url) }}" />
			</a>
			@endif
			@error('receipt') <span class="error">{{ $message }}</span> @enderror
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
			<select wire:model="linkedsubitems" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500 form-select-engineerSearch">
				<option value="">-- Select Linked Sub Items--</option>    
				@foreach (App\Models\Item::getlinkeditems() as $key => $value)
				<option value="{{ $value['id'] }}">{{ $value['name'] }}</option>    
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
		
		<x-jet-danger-button class="ml-2" wire:click="deleteSubitem" wire:loading.attr="disabled">
			{{ __('Confirm delete') }}
		</x-jet-danger-button>
	</x-slot>
</x-jet-dialog-modal>


{{-- The Barcode Modal --}}
<x-jet-dialog-modal wire:model="modalBarcodeVisible">
	<x-slot name="title">
		{{ __('Barcode') }}
	</x-slot>
	
	<x-slot name="content">
		@if(@$this->barcode_url)
		<a target="_blank" href="{{ asset('assets/barcodes/'.$this->barcode_url) }}">
			<img src="{{ asset('assets/barcodes/'.$this->barcode_url) }}" />
		</a>
		@endif
	</x-slot>
	
	<x-slot name="footer">
		<x-jet-secondary-button wire:click="$toggle('modalBarcodeVisible')" wire:loading.attr="disabled">
			{{ __('Close') }}
		</x-jet-secondary-button>
	</x-slot>
</x-jet-dialog-modal>

@push('scripts')
<script>

	function select2() {
	
		$('.form-select-engineerSearch').select2({width: '100%', sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),});
		$('.form-select-engineerSearch').on('change', function (e) {
			var data = $('.form-select-engineerSearch').select2("val");
			@this.set('linkedsubitems', data);
		});
	
    };
    window.addEventListener('turbolinks:load', select2);
</script>
@endpush