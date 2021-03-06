<div class="p-6">    
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create User Permission') }}
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
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('role')" role="button" href="#">
								Role
								@include('includes._sort-icon', ['field' => 'role'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('route_name')" role="button" href="#">
								Route Name
								@include('includes._sort-icon', ['field' => 'route_name'])
							</a></th>
							
							<th class="px-4 py-2"><a wire:click.prevent="sortBy('created_at')" role="button" href="#">
								Created At
								@include('includes._sort-icon', ['field' => 'created_at'])
							</a></th>
							
							<th class="px-4 py-2">Action</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($permissions as $permission)
						<tr>
							<td class="border px-4 py-2">{{ $permission->role }}</td>
							<td class="border px-4 py-2">{{ $permission->route_name }}</td>
							<td class="border px-4 py-2">{{ $permission->created_at ? $permission->created_at->format('m/d/Y') : '' }}</td>
							<td class="border px-4 py-2">
								<button wire:click="updateShowModal({{ $permission->id }})">
									<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
								</button>
								
								<button wire:click="deleteShowModal({{ $permission->id }})">
									<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
								</button>
								
							</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
				
				<div class="col">
					{{ $permissions->links() }}
				</div>
				
			</div>
		</div>
	</div>






	{{-- Modal Form --}}
	<x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Save User Permission') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="role" value="{{ __('Role') }}" />
                <select wire:model="role" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select a Role --</option>
                    @foreach (App\Models\User::userRoleList() as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('role') <span class="error">{{ $message }}</span> @enderror
            </div>  
            <div class="mt-4">
                <x-jet-label for="routeName" value="{{ __('Route Name') }}" />
                <select wire:model="routeName" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select a Route --</option>    
                    @foreach (App\Models\UserPermission::routeNameList() as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
                @error('routeName') <span class="error">{{ $message }}</span> @enderror
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
			{{ __('Delete User Permission') }}
		</x-slot>
		
		<x-slot name="content">
			{{ __('Are you sure you want to delete this user permission?') }}
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

</div>
