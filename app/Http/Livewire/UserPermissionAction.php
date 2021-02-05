<?php

namespace App\Http\Livewire;

use App\Models\UserPermission;
use Livewire\Component;
use Livewire\WithPagination;

class UserPermissionAction extends Component
{
	use WithPagination;

	public $perPage = 10;
    public $sortField='id';
    public $sortAsc = true;
    public $search = '';
	
    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;

    /**
     * Put your custom public properties here!
     */
    public $role;
    public $routeName;
	
	
	/**
     * The validation rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'role' => 'required',
            'routeName' => 'required',
        ];
    }

    /**
     * Loads the model data
     * of this component.
     *
     * @return void
     */
    public function loadModel()
    {
        $data = UserPermission::find($this->modelId);
        // Assign the variables here
        $this->role = $data->role;
        $this->routeName = $data->route_name;
    }

    /**
     * The data for the model mapped
     * in this component.
     *
     * @return void
     */
    public function modelData()
    {
        return [
            'role' => $this->role,
            'route_name' => $this->routeName,
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
        UserPermission::create($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
		redirect()->to('/user-permissions');
    }
	
	/**
     * The update function
     *
     * @return void
     */
    public function update()
    {
        $this->validate();
        UserPermission::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        UserPermission::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
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
     * Shows the form modal
     * in update mode.
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id)
    {
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible = true;
        $this->modelId = $id;
        $this->loadModel();
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
        return view('livewire.user-permission-action', [
            'permissions' => UserPermission::where(function ($query) use ($search) {
					$query->where('role', 'like', '%'.$search.'%')
					->orWhere('route_name', 'like', '%'.$search.'%')
					->orWhere('created_at', 'like', '%'.$search.'%');
				})
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ]);
		
		
    }
}
