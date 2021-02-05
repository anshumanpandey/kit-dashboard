<div class="p-6">    
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create Project') }}
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
						
						<select wire:model="search_type" class="border border-gray-200 mt-1 sm:px-6">
							<option>Select Project Type</option>
							<option value="future">In Future</option>
							<option value="live">Live</option>
							<option value="archived">Archived</option>
						</select>
					</div>
					
					<div class="col">
						<x-jet-input wire:model="search" class="block mt-1 mb-6" type="text" placeholder="Search..."/>
					</div>
				</div>
				
				<table class="table table-responsive w-full">
					<thead>
						<tr class="bg-gray-100">
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('title')" role="button" href="#">
								Title
								@include('includes._sort-icon', ['field' => 'title'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('pickup_date')" role="button" href="#">
								Pickup Date
								@include('includes._sort-icon', ['field' => 'pickup_date'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('start_date')" role="button" href="#">
								Start Date
								@include('includes._sort-icon', ['field' => 'start_date'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('end_date')" role="button" href="#">
								End Date
								@include('includes._sort-icon', ['field' => 'end_date'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('expected_return_date')" role="button" href="#">
								Expected Return Date
								@include('includes._sort-icon', ['field' => 'expected_return_date'])
							</a></th>
							
							<th class="px-4 py-2">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($projects as $project)
						<tr>
							<td class="border px-4 py-2">{{ @$project->title }}</td>
							<td class="border px-4 py-2">{{ \Carbon\Carbon::parse($project->pickup_date)->format('d/m/Y')}}</td>
							<td class="border px-4 py-2">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y')}}</td>
							<td class="border px-4 py-2">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y')}}</td>
							<td class="border px-4 py-2">{{ \Carbon\Carbon::parse($project->expected_return_date)->format('d/m/Y')}}</td>
							
							<td class="border px-4 py-2">
								<button wire:click="updateShowModal({{ $project->id }})">
									<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
								</button>
								
								<button wire:click="createCopyModal({{ $project->id }})">
									<i class="fa fa-clipboard" aria-hidden="true"></i>
								</button>
								
								<button wire:click="historyModal({{ $project->id }})">
									<i class="fa fa-list-alt" aria-hidden="true"></i>
								</button>
								
								<button wire:click="deleteShowModal({{ $project->id }})">
									<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
								</button>
								
							</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
				
				<div class="col">
					{{ $projects->links() }}
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
									<th>Available Qty</th>
									<th></th>
								</tr>
							</thead>
                            <tbody>
								@foreach ($stockProducts as $index => $stockProduct)
                                <tr class="stockProduct">
                                    <td>
                                        <select name="stockProducts[{{$index}}][id]"
										wire:model="stockProducts.{{$index}}.id"
										wire:change="checkProduct({{$index}})"
										class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500 stockProductsSelect"> 
											<option value="">-- Select a Items --</option>
											@foreach (App\Models\Project::getSubItems() as $key => $value)
											<option value="{{ $value->id }}" data-qty="{{ $value->quantity }}" data-available="{{ $value->available_qty }}" data-index="{{$index}}">
												{{ $value->items->name }} - {{ $value->serialnumber }}
											</option>
											@endforeach
										</select>
									</td>
                                    <td>
                                        <input type="number"
										name="stockProducts[{{$index}}][quantity]"
										wire:keyup="checkQty({{$index}})"
										class="block stock_qty appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
										wire:model="stockProducts.{{$index}}.quantity"/>
									</td>
									<td>
                                        {{ @$stockProducts[$index]['avl_quantity'] }}
									</td>
                                    <td>
                                        <a href="#" wire:click.prevent="removeProduct({{$index}}, {{$stockProduct['id']}})">Delete</a>
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
			
            @if ($modelId && !$copyProject)
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
			{{ __('Delete Project') }}
		</x-slot>
		
		<x-slot name="content">
			{{ __('Are you sure you want to delete this project?') }}
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
	
	
	
	{{-- The History Modal --}}
	<x-jet-dialog-modal wire:model="modalHistoryVisible">
		<x-slot name="title">
			{{ __('Project History') }}
		</x-slot>
		
		<x-slot name="content">
			<table class="table" id="products_table">
				<thead>
					<tr>
						<th>Message</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($history as $his)
					<tr>
						<td>
						{{ $his->notificationtext }}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</x-slot>
		
		<x-slot name="footer">
			<x-jet-secondary-button wire:click="$toggle('modalHistoryVisible')" wire:loading.attr="disabled">
				{{ __('Close') }}
			</x-jet-secondary-button>
		</x-slot>
	</x-jet-dialog-modal>
	

</div>
