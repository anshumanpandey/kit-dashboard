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

            <x-jet-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
                {{ __('Confirm Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
