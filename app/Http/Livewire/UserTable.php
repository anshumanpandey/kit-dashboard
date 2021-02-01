<?php

namespace App\Http\Livewire;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Hash;

class UserTable extends LivewireDatatable
{
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;

    public $role;
    public $name;
    public $email;
    public $password;
    protected $created_by;
    protected $updated_by;
    public $organization;

    public function builder()
    {
        return (auth()->user()->role == 'admin') ? User::with('organizations') : User::with('organizations')->where('role','user');
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
    public function deleteUser()
    {
        User::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
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

    public function columns()
    {
        return [
            NumberColumn::name('id'),

            Column::name('name')->searchable(),
            Column::name('email')->searchable(),
            Column::name('organizations.name')->searchable()->label('Organization Name'),

            DateColumn::name('created_at'),

            Column::callback(['id', 'name'], function ($id, $name) {
                return view('UserTable-actions', ['id' => $id, 'modelId' => $id, 'name' => $name]);
            })
        ];
    }
}