<?php

namespace App\Http\Livewire;

use App\Models\UserPermission;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Hash;

class UserPermissionsTable extends LivewireDatatable
{
    public $model = UserPermission::class;

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
    public function deletePermission()
    {
        UserPermission::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
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

    public function columns()
    {
        return [
            Column::name('role')->searchable(),
            Column::name('route_name')->searchable(),

            DateColumn::name('created_at'),

            Column::callback(['id', 'route_name'], function ($id, $route_name) {
                return view('UserPermissionsTable-actions', ['id' => $id, 'modelId' => $id, 'route_name' => $route_name]);
            })
        ];
    }
}