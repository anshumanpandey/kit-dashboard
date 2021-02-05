<?php

namespace App\Http\Livewire;

use App\Models\User;
use Auth;
use Hash;


use Livewire\Component;
use Livewire\WithPagination;

class UsersAction extends Component
{
    use WithPagination;
	
	public $perPage = 10;
    public $sortField='id';
    public $sortAsc = true;
    public $search = '';
	
	
	public $modalFormVisible=false;
    public $modalConfirmDeleteVisible=false;
    public $modelId;

    /**
     * Put your custom public properties here!
     */
    public $role;
    public $name;
    public $email;
    public $password;
    protected $created_by;
    protected $updated_by;
    public $organization;
	
	
	
	/**
     * This used to store the created by
     * @return currentUser
     */
    public function mount()
    {
        $this->created_by = auth()->user()->id;
        $this->updated_by = auth()->user()->id;
    }
    /**
     * The validation rules
     *
     * @return void
     */
    public function rules()
    {
		return [
			'role' => 'required',
			'name' => 'required',
			'email' => 'required',
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
        $data = User::find($this->modelId);
        $this->role = $data->role;
        $this->name = $data->name;
        $this->email = $data->email;
        $this->password = $data->password;
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
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'organization_id' => $this->organization
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
        // adding new column entry
        User::create($this->appendArray());
        $this->modalFormVisible = false;
        $this->reset();
        redirect()->to('/users');
    }
	
	public function appendArray()
    {
        $auth_user = ['created_by' => auth()->user()->id, 'updated_by' => auth()->user()->id, 'current_team_id' => 1];
        return array_merge($this->modelData(), $auth_user);
    }
	
	/**
     * The update function
     *
     * @return void
     */
    public function update()
    {
        $this->validate();
        User::find($this->modelId)->update($this->appendArray());
        $this->modalFormVisible = false;
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
	
	/**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        User::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
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
		if(Auth::user()->role=='admin'){
			return view('livewire.users-action', [
				'users' => User::with('organizations')
					->where(function ($query) use ($search) {
						$query->where('name', 'like', '%'.$search.'%')
						->orWhere('email', 'like', '%'.$search.'%');
					})
					->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
					->paginate($this->perPage),
			]);
		}
        return view('livewire.users-action', [
			'users' => User::with('organizations')
				->where('organization_id', '=', Auth::user()->organization_id)
				->where(function ($query) use ($search) {
					$query->where('name', 'like', '%'.$search.'%')
					->orWhere('email', 'like', '%'.$search.'%');
				})
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ]);
    }
	
}
