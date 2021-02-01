<?php

namespace App\Http\Livewire;

use App\Models\Organization;
use Livewire\Component;
use Livewire\WithPagination;
use Auth;

class OrganizationComponent extends Component
{
    use WithPagination;
    
    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;
    
    public $name;

    /**
     * The data for the model mapped
     * in this component.
     *
     * @return void
     */
    public function modelData()
    {
        return [          
            'name' => $this->name
        ];
    }
    
    /**
     * The validation rules
     *
     * @return void
     */
    public function rules()
    {
        return [     
            'name' => 'required'       
        ];
    }

    /**
     * The read function.
     *
     * @return void
     */
    public function read()
    {
        return Organization::paginate(5);
    }

    /**
     * The create function.
     *
     * @return void
     */
    public function create()
    {
        $this->validate();
        $auth_user = ['created_by' => auth()->user()->id, 'updated_by' => auth()->user()->id];
        $newUser = array_merge($this->modelData(), $auth_user);
        Organization::create($newUser);
        $this->modalFormVisible = false;
        redirect()->to('/organizations');
    }

    /**
     * Shows the create modal
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible = true;
    } 

    public function render()
    {
        return view('livewire.organization-component', [
            'data' => $this->read(),
        ]);
    }
}