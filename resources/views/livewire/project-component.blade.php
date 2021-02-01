<div class="p-6">    
	
	<div class="text-left" style="display: inline; position: absolute;">
		<select class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" name="items" >
			<option>Select Project Type</option>
			<option>In Future</option>
			<option>Live</option>
			<option>Archived</option>			
		</select>
	</div>
	
	<div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">	
		<x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
		</x-jet-button>
		
		
		
		
	</div>
	
    {{-- The data table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <livewire:project-table per-page="10" />
				</div>
			</div>
		</div>
	</div>
	
    {{-- Modal Form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Create or Update Form') }}
		</x-slot>
		
        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Label') }}" />
                <x-jet-input type="text" name="title" wire:model.lazy="title" value="{{ ($title) ? $title : '' }}" class="block mt-1 w-full" placeholder="Enter project title" />
                @error('title') <span class="error">{{ $message }}</span> @enderror
			</div>  
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Select Pickup Date ') }}" />
                <input type="date" name="pickup_date" wire:model="pickup_date" autocomplete="off" value="{{ ($pickup_date) ? $pickup_date : '' }}" min="{{ date('Y-m-d') }}" placeholder="Select pick date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
                @error('pickup_date') <span class="error">{{ $message }}</span> @enderror
			</div>
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Select Shipping Date ') }}" />
                <input type="date" name="shipping_date" wire:model="shipping_date" autocomplete="off" value="{{ ($shipping_date) ? $shipping_date : '' }}" min="{{ date('Y-m-d') }}" placeholder="Select shipping date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
                @error('shipping_date') <span class="error">{{ $message }}</span> @enderror
			</div>
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Select Start Date ') }}" />
                <input type="date" name="start_date" wire:model="start_date" autocomplete="off" value="{{ ($start_date) ? $start_date : '' }}" min="{{ date('Y-m-d') }}" placeholder="Select start date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">            
                @error('start_date') <span class="error">{{ $message }}</span> @enderror
			</div>
            <div class="mt-4">
				<x-jet-label for="" value="{{ __('Select End date') }}" />
				<input type="date" name="end_date" wire:model="end_date" autocomplete="off"  value="{{ ($end_date) ? $end_date : '' }}" min="{{ date('Y-m-d') }}" placeholder="Select end date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
                @error('end_date') <span class="error">{{ $message }}</span> @enderror
			</div>
			<div class="mt-4">
				<x-jet-label for="" value="{{ __('Expire Return date') }}" />
				<input type="date" name="exp_return_date"  wire:model="exp_return_date" autocomplete="off" value="{{ ($this->exp_return_date) ? $this->exp_return_date : '' }}"  placeholder="Select expected date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
                @error('exp_return_date') <span class="error">{{ $message }}</span> @enderror
			</div>
			<div class="mt-4">
				<div class="row">
					<div class="col-6">
                        <table class="table" id="products_table">
                            <thead>
								<tr>
									<th>Items</th>
									<th>Quantity</th>
									<th></th>
								</tr>
							</thead>
                            <tbody>
								@foreach ($stockProducts as $index => $stockProduct)
                                <tr>
                                    <td>
                                        <select name="stockProducts[{{$index}}][id]"
										wire:model="stockProducts.{{$index}}.id"
										wire:change="checkProduct"
										class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500 stockProductsSelect"> 
											<option value="">-- Select a Items --</option>
											@foreach (App\Models\Project::getItems() as $key => $value)
											<option value="{{ $value->id }}">
												{{ $value->name }}
											</option>
											@endforeach
										</select>
									</td>
                                    <td>
                                        <input type="number"
										name="stockProducts[{{$index}}][quantity]"
										class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
										wire:model="stockProducts.{{$index}}.quantity"/>
									</td>
                                    <td>
                                        <a href="#" wire:click.prevent="removeProduct({{$index}})">Delete</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
                        <div class="row">
                            <div class="col-md-12">
								<x-jet-secondary-button class="ml-2" wire:click.prevent="addProduct" wire:loading.attr="disabled">
									{{ __('+  Add Another Item')}}
								</x-jet-secondary-button>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
		</x-slot>
		
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Close') }}
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
            {{ __('Delete Modal Title') }}
		</x-slot>
		
        <x-slot name="content">
            {{ __('Are you sure you want to delete this item?') }}
		</x-slot>
		
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
			</x-jet-secondary-button>
			
            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete Item') }}
			</x-jet-danger-button>
		</x-slot>
	</x-jet-dialog-modal>
</div>
<script type="text/javascript">
	//---
    function Datepicker() {
        $('#datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD H:mm'
			}).on("dp.change", function (e) {
            @this.set('pay_date', e.date);
		});
	};
    window.addEventListener('turbolinks:load', Datepicker);
</script>