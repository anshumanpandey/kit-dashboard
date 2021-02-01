<div class="p-6">    
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create User') }}
        </x-jet-button>
    </div>
    {{-- The data table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <livewire:user-table per-page="10" />
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Create or Update User') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input wire:model="name" id="" class="block mt-1 w-full" type="text" />
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>           
            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input wire:model="email" id="" class="block mt-1 w-full" type="text" />
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div> 
            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input wire:model="password" id="" class="block mt-1 w-full" type="password" />
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div> 
            <div class="mt-4">
                <x-jet-label for="role" value="{{ __('Role') }}" />
                <select wire:model="role" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select a Role --</option>    
                    @if(auth()->user()->role != 'admin')
                    @foreach (App\Models\User::managerRolelist() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>    
                    @endforeach
                    @else 
                    @foreach (App\Models\User::userRoleList() as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>    
                    @endforeach
                    @endif                   
                </select>
                @error('role') <span class="error">{{ $message }}</span> @enderror
            </div>      
            <div class="mt-4">
                <x-jet-label for="organization" value="{{ __('Organization') }}" />
                

                    @if(auth()->user()->role != 'admin') 
                    <select wire:model="organization" id="" disabled="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    
                    @foreach (App\Models\User::managerOrganization() as $key => $value)
                    <option value="{{ $value->id }}" selected="selected" >{{ $value->name }}</option>    
                    @endforeach
                     </select>
                    
                    @else
                    <select wire:model="organization" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Select a Organization --</option>  
                    @foreach (App\Models\User::userOrganization() as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>    
                    @endforeach
                     </select>
                    @endif 
               
                @error('role') <span class="error">{{ $message }}</span> @enderror
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
                            {{ __('Delete User') }}
                        </x-slot>

                        <x-slot name="content">
                            {{ __('Are you sure you want to delete this item?') }}
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
