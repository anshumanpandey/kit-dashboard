<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Organization;

use Livewire\WithPagination;
use Auth;

class OrganizationsAction extends Component
{
	use WithPagination;
	
	public $perPage = 10;
    public $sortField='name';
    public $sortAsc = true;
    public $search = '';
	public $model = Organization::class;
	
	
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
	
	/**
     * Shows the delete confirmation modal.
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }   

    /**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        Organization::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }
	
	
	/**
     * Loads the model data
     * of this component.
     *
     * @return void
     */
    public function loadModel()
    {
        $data = Organization::find($this->modelId);
        // Assign the variables here
        $this->name = $data->name;

    }

    /**
     * The update function
     *
     * @return void
     */
    public function update()
    {
        $this->validate();
        $auth_user = ['created_by' => auth()->user()->id, 'updated_by' => auth()->user()->id];
        $newUser = array_merge($this->modelData(), $auth_user);
        Organization::find($this->modelId)->update($newUser);
        $this->modalFormVisible = false;
    }
	
	/**
     * Shows the form modal
     * in update mode.
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id)
    {
        $this->modalFormVisible = true;
        $this->modelId = $id;
        $this->loadModel();
    }
	
	public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
	

    public function render()
    {
		$search = $this->search;
        return view('livewire.organizations-action', [
            'organizations' => Organization::where(function ($query) use ($search) {
					$query->where('name', 'like', '%'.$search.'%')
					->orWhere('created_by', 'like', '%'.$search.'%');
				})
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ]);
    }
}
