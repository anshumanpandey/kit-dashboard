<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Hash;
use Auth;

class ItemsTable extends LivewireDatatable
{
    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;
    public $name;
    public $item_type_id;
    public $make;
    public $model;
    public $cable_length;
    public $linked_items = [];

    public function builder()
    {
        return Item::latest()->where('organisation_id', '=', Auth::user()->organization_id);
    }

    /**
     * Put your custom public properties here!
     */

    /**
     * The validation rules
     *
     * @return void
     */
    public function rules() {
        return [
            'name' => 'required',
            'item_type_id' => 'required'
        ];
    }

    /**
     * Loads the model data
     * of this component.
     *
     * @return void
     */
    public function loadModel() {
        $data = Item::find($this->modelId);

		$linkeditems = [];
		foreach($data->linkeditems as $linked){
			array_push($linkeditems, $linked->linked_item_id);
		}
		
        // Assign the variables here
        $this->name = $data->name;
        $this->item_type_id = $data->item_type_id;
        $this->make = $data->make;
        $this->model = $data->model;
        $this->cable_length = $data->cable_length;
        $this->linked_items = $linkeditems;
    }

    /**
     * The data for the model mapped
     * in this component.
     *
     * @return void
     */
    public function modelData() {
        return [
            'name' => $this->name,
            'item_type_id' => $this->item_type_id,
            'make' => $this->make,
            'model' => $this->model,
            'cable_length' => $this->cable_length,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'organisation_id' => auth()->user()->organization_id,
            'status' => 'available'
        ];
    }

    /**
     * The create function.
     *
     * @return void
     */
    public function create() {

        $this->validate();
        $item = Item::create($this->modelData());
        if ($item) {
            $saveLinked = [];
            foreach ($this->linked_items as $key => $linkedItems) {
                $saveLinked['item_id'] = $item->id;
                $saveLinked['linked_item_id'] = $linkedItems;
                $saveLinked['created_by'] = auth()->user()->id;
                $saveLinked['updated_by'] = auth()->user()->id;
                
                Linkeditem::create($saveLinked);
            }
        }

        $this->modalFormVisible = false;
        $this->reset();
    }

        /**
     * The delete function.
     *
     * @return void
     */
    public function deleteItem() {
        Item::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
        redirect()->to('/items');
    }

    /**
     * Shows the delete confirmation modal.
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id) {
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

    /**
     * The update function
     *
     * @return void
     */
    public function update() {
        $this->validate();
        $auth_user = ['created_by' => auth()->user()->id, 'updated_by' => auth()->user()->id];
        $newItem = array_merge($this->modelData(), $auth_user);
        Item::find($this->modelId)->update($newItem);
		
		if ($this->modelId) {
            $saveLinked = [];
            foreach ($this->linked_items as $key => $linkedItems) {
                $saveLinked['item_id'] = $this->modelId;
                $saveLinked['linked_item_id'] = $linkedItems;
                $saveLinked['created_by'] = auth()->user()->id;
                $saveLinked['updated_by'] = auth()->user()->id;
                
                Linkeditem::create($saveLinked);
            }
        }
		
        $this->modalFormVisible = false;
        redirect()->to('/items');
    }

    /**
     * Shows the form modal
     * in update mode.
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id) {
        $this->modalFormVisible = true;
        $this->modelId = $id;
        $this->loadModel();
    }

    public function columns()
    {
         
        return [
            Column::name('name')->searchable(),
            Column::name('cable_length')->searchable(),
            Column::name('make')->searchable(),
            Column::name('model')->searchable(),
            Column::name('status')->searchable(),

            DateColumn::name('created_at'),

            
            Column::callback(['id', 'item_type_id'], function ($id, $item_type_id) {
                return view('ItemsTable-actions', ['id' => $id, 'modelId' => $id, 'item_type_id' => $item_type_id ]);
            })
        ];
    }
}