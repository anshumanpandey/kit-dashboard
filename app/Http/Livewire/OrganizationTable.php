<?php

namespace App\Http\Livewire;

use App\Models\Organization;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OrganizationTable extends LivewireDatatable
{
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
    public function deleteOrg()
    {
        Organization::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    public function columns()
    {
        return [
            NumberColumn::name('id'),

            Column::name('name')->searchable(),

            DateColumn::name('created_at'),

            Column::callback(['id', 'name'], function ($id, $name) {
                return view('OrganizationTable-actions', ['id' => $id, 'modelId' => $id, 'name' => $name]);
            })
        ];
    }
}