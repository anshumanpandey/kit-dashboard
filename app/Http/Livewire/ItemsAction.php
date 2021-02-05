<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Linkeditem;
use Auth;

use Livewire\Component;
use Livewire\WithPagination;

class ItemsAction extends Component
{

	use WithPagination;
	
	public $perPage = 10;
    public $sortField='name';
    public $sortAsc = true;
    public $search = '';
	
	/**
     * Put your custom public properties here!
     */
	 
	public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;
    public $name;
    public $item_type_id;
    public $make;
    public $model;
    public $cable_length;
    public $linked_items = [];
	
	
	
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
	
        // Assign the variables here
        $this->name = $data->name;
        $this->item_type_id = $data->item_type_id;
        $this->make = $data->make;
        $this->model = $data->model;
        $this->cable_length = $data->cable_length;
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
        redirect()->to('/items');
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
        $this->modalFormVisible = false;
    }

    /**
     * The delete function.
     *
     * @return void
     */
    public function delete() {
        Item::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
        redirect()->to('/items');
    }

    /**
     * Shows the create modal
     *
     * @return void
     */
    public function createShowModal() {
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
    public function updateShowModal($id) {
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
    public function deleteShowModal($id) {
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
        return view('livewire.items-action', [
            'items' => Item::where('organisation_id', '=', Auth::user()->organization_id)
				->where(function ($query) use ($search) {
					$query->where('name', 'like', '%'.$search.'%')
					->orWhere('cable_length', 'like', '%'.$search.'%')
					->orWhere('make', 'like', '%'.$search.'%')
					->orWhere('model', 'like', '%'.$search.'%');
				})
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ]);
    }
	
}
