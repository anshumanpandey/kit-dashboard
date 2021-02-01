<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Auth;
use Hash;
class Users extends Component
{
    use WithPagination;

    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
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
            'email' => 'required'
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
     * The read function.
     *
     * @return void
     */
    public function read()
    {
        return (auth()->user()->role == 'admin') ? User::with('organizations')->paginate(5) : User::with('organizations')->where('role','user')->paginate(5);
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

    public function render()
    {
        return view('livewire.users', [
            'data' => $this->read(),
        ]);
    }
}
