<div class="flex space-x-1 justify-around">
    
    <a wire:click="updateShowModal({{ $id }})" target="_blank" class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
	</a>
	
    <button wire:click="deleteShowModal({{ $id }})" class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
	</button>
</div>
{{-- Modal Form --}}
<x-jet-dialog-modal wire:model="modalFormVisible">
	<x-slot name="title">
		{{ __('Create or Update Form') }}
	</x-slot>
	
	<x-slot name="content">
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Label') }}" />
			<x-jet-input type="text" name="title" wire:model.lazy="title" value="{{ ($this->title) ? $this->title : '' }}" class="block mt-1 w-full" placeholder="Enter project title" />
                @error('title') <span class="error">{{ $message }}</span> @enderror
			</div>  
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Select Pickup Date ') }}" />
                <input type="date" name="pickup_date" wire:model="pickup_date" autocomplete="off" value="{{ ($this->pickup_date) ? $this->pickup_date : '' }}" placeholder="Select pick date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
                @error('pickup_date') <span class="error">{{ $message }}</span> @enderror
			</div>
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Select Shipping Date ') }}" />
                <input type="date" name="shipping_date" wire:model="shipping_date" autocomplete="off" value="{{ ($this->shipping_date) ? $this->shipping_date : '' }}"  placeholder="Select shipping date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
                @error('shipping_date') <span class="error">{{ $message }}</span> @enderror
			</div>
			
            <div class="mt-4">
                <x-jet-label for="" value="{{ __('Select Start Date ') }}" />
                <input type="date" name="start_date" wire:model="start_date" autocomplete="off" value="{{ ($this->start_date) ? $this->start_date : '' }}"  placeholder="Select start date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">            
                @error('start_date') <span class="error">{{ $message }}</span> @enderror
			</div>
            <div class="mt-4">
				<x-jet-label for="" value="{{ __('Select End date') }}" />
				<input type="date" name="end_date" wire:model="end_date" autocomplete="off"  value="{{ ($this->end_date) ? $this->end_date : '' }}"   placeholder="Select end date" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
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
									<th>Delete</th>
								</tr>
							</thead>
                            <tbody>
								@foreach ($this->ProjectItem as $i => $items)
								<tr>
									<td>
                                        {{$items->item->name}}
									</td>
									<td>
										{{$items->quantity}}
									</td>
									<td>
										<button class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
											<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
										</button>
									</td>
								</tr>
								@endforeach
								
								@foreach ($this->stockProducts as $index => $stockProduct)
								{{--
                                <tr>
                                    <td>
                                        <select name="stockProducts[{{$index}}][id]"
										wire:model="stockProducts.{{$index}}.id"
										wire:change="checkProduct"
										class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"> 
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
								--}}
								@endforeach
							</tbody>
						</table>
                        <div class="row">
                            <div class="col-md-12">
								<x-jet-secondary-button class="ml-2"
								wire:click.prevent="addProduct" wire:loading.attr="disabled">{{ __('+  Add Another Item')}}</x-jet-secondary-button>
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
                {{ __('Cancle') }}
			</x-jet-secondary-button>
			
            <x-jet-danger-button class="ml-2" wire:click="deleteProject" wire:loading.attr="disabled">
                {{ __('Delete Item') }}
			</x-jet-danger-button>
		</x-slot>
	</x-jet-dialog-modal>
	<script type="text/javascript">
		function Datepicker() {
			$('#datetimepicker').datetimepicker({
				format: 'YYYY-MM-DD H:mm'
				}).on("dp.change", function (e) {
				@this.set('pay_date', e.date);
			});
		};
		window.addEventListener('turbolinks:load', Datepicker);
	</script>	