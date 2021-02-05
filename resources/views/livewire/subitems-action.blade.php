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
				
				<div class="mb-6">
					<div class="col form-inline">
						Per Page: &nbsp;
						<select wire:model="perPage" class="border border-gray-200 mt-1 sm:px-6">
							<option>10</option>
							<option>15</option>
							<option>25</option>
						</select>
					</div>
					
					<div class="col">
						<x-jet-input wire:model="search" class="block mt-1 mb-6" type="text" placeholder="Search..."/>
					</div>
				</div>
				
				<table class="table table-responsive w-full">
					<thead>
						<tr class="bg-gray-100">
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('serialnumber')" role="button" href="#">
								Serial Number
								@include('includes._sort-icon', ['field' => 'serialnumber'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('date_of_purchase')" role="button" href="#">
								Date of Purchase
								@include('includes._sort-icon', ['field' => 'date_of_purchase'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('warranty_expiry_period')" role="button" href="#">
								Warranty Expiry Period
								@include('includes._sort-icon', ['field' => 'warranty_expiry_period'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('quantity')" role="button" href="#">
								Quantity
								@include('includes._sort-icon', ['field' => 'quantity'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('available_qty')" role="button" href="#">
								Available
								@include('includes._sort-icon', ['field' => 'available_qty'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('condition')" role="button" href="#">
								Condition
								@include('includes._sort-icon', ['field' => 'condition'])
							</a></th>
							{{--
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('status')" role="button" href="#">
								Status
								@include('includes._sort-icon', ['field' => 'status'])
							</a></th>
							--}}
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('created_at')" role="button" href="#">
								Created At
								@include('includes._sort-icon', ['field' => 'created_at'])
							</a></th>
							
							<th class="px-4 py-2">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($subitems as $item)
						<tr>
							<td class="border px-4 py-2">{{ @$item->serialnumber }}</td>
							<td class="border px-4 py-2">{{ @$item->date_of_purchase }}</td>
							<td class="border px-4 py-2">{{ @$item->warranty_expiry_period }}</td>
							<td class="border px-4 py-2">{{ @($item->quantity) }}</td>
							<td class="border px-4 py-2">{{ @($item->available_qty) }}</td>
							<td class="border px-4 py-2">{{ @($item->condition) }}</td>
								{{--<td class="border px-4 py-2">{{ @($item->status) }}</td>--}}
							<td class="border px-4 py-2">{{ $item->created_at ? $item->created_at->format('m/d/Y') : '' }}</td>
							<td class="border px-4 py-2">
								<button title="Edit" wire:click="updateShowModal({{ $item->id }})">
									<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
								</button>
								
								<button title="Barcodes" wire:click="barcodeShowModal({{ $item->id }})">
									<i class="fa fa-barcode" aria-hidden="true"></i>
								</button>
								
								<button title="List Items" wire:click="listItemsShowModal({{ $item->id }})">
									<i class="fa fa-list" aria-hidden="true"></i>
								</button>
								
								<button title="Delete" wire:click="deleteShowModal({{ $item->id }})">
									<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
								</button>
								
							</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
				
				<div class="col">
					{{ $subitems->links() }}
				</div>
				
			</div>
		</div>
	</div>



	{{-- Modal Form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Create or Update Subitem') }}
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
				@if(@$this->pictureurlImg)
				<a target="_blank" href="{{ asset('storage/photos/'.$this->pictureurlImg) }}">
					<img width="200" src="{{ asset('storage/photos/'.$this->pictureurlImg) }}" />
				</a>
				@endif
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
				@if(@$this->receipt_urlImg)
				<a target="_blank" href="{{ asset('storage/photos/'.$this->receipt_urlImg) }}">
					<img width="200" src="{{ asset('storage/photos/'.$this->receipt_urlImg) }}" />
				</a>
				@endif
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
			
			{{--
            <div class="mt-4">
                <select wire:model="status" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select Status --</option>    
                    @foreach (App\Models\Subitem::getStatus() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>    
                    @endforeach
				</select> 
                @error('status') <span class="error">{{ $message }}</span> @enderror
			</div>
			--}}
			
            <div class="mt-4" wire:ignore>
                <select wire:model="linkedsubitems" id="" multiple="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500 form-select-engineerSearch">
                    <option value="">-- Select Linked Sub Items--</option>
                    @foreach(App\Models\Subitem::getlinkedsubitems() as $key => $value)
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
            <x-jet-danger-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                {{ __('Update') }}
			</x-jet-danger-button>
			@else
			<x-jet-danger-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
				{{ __('Create') }}
			</x-jet-danger-button>
			@endif            
		</x-slot>
	</x-jet-dialog-modal>

	{{-- The Delete Modal --}}
	<x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
		<x-slot name="title">
			{{ __('Delete Subitem') }}
		</x-slot>
		
		<x-slot name="content">
			{{ __('Are you sure you want to delete this subitem?') }}
		</x-slot>
		
		<x-slot name="footer">
			<x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
				{{ __('Cancel') }}
			</x-jet-secondary-button>
			
			<x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
				{{ __('Confirm Delete') }}
			</x-jet-danger-button>
		</x-slot>
	</x-jet-dialog-modal>

	{{-- The Barcode Modal --}}
	<x-jet-dialog-modal wire:model="modalBarcodeVisible">
		<x-slot name="title">
			{{ __('Barcode') }}
		</x-slot>
		
		<x-slot name="content">
			<div class="barcode-img" id="printBarcodes">
				@foreach($allBarcodes as $barcode)
				<a class="barcode" target="_blank" href="{{ asset('assets/barcodes/'.$barcode->barcode_url) }}" style="margin-bottom: 10px; padding: 10px; display: inherit;">
					<img src="{{ asset('assets/barcodes/'.$barcode->barcode_url) }}" />
				</a>
				@endforeach
			</div>
		</x-slot>
		
		<x-slot name="footer">
			<x-jet-secondary-button wire:click="$toggle('modalBarcodeVisible')" wire:loading.attr="disabled">
				{{ __('Close') }}
			</x-jet-secondary-button>
			<x-jet-danger-button onclick="printBarcodes()">
				{{ __('Print') }}
			</x-jet-danger-button>
		</x-slot>
	</x-jet-dialog-modal>
	
	
	{{-- The List Items Modal --}}
	<x-jet-dialog-modal wire:model="modalListItemsShowModal">
		<x-slot name="title">
			{{ __('List Items') }}
		</x-slot>
		
		<x-slot name="content">
			<table class="table table-responsive w-full" id="products_table">
				<thead>
					<tr class="bg-gray-100">
						<th class="px-4 py-2">Barcode Number</th>
						<th class="px-4 py-2">Status</th>
						<th class="px-4 py-2">History</th>
					</tr>
				</thead>
				<tbody>
					@foreach($allBarcodes as $barcode)
					<tr>
						<td class="border px-4 py-2">{{ @$barcode->barcode_no }}</td>
						<td class="border px-4 py-2"></td>
						<td class="border px-4 py-2"></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</x-slot>
		
		<x-slot name="footer">
			<x-jet-secondary-button wire:click="$toggle('modalListItemsShowModal')" wire:loading.attr="disabled">
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
		
		function printBarcodes(){
			var divToPrint=document.getElementById('printBarcodes');
			var newWin=window.open('','Print-Window');
			newWin.document.open();
			newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
			newWin.document.close();
			setTimeout(function(){newWin.close();},30);
		}
	</script>
	@endpush
</div>
