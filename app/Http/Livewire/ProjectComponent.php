<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use App\Models\Linkeditem;

class ProjectComponent extends Component
{
    use WithPagination;
    
    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;

     /**
     * Put your custom public properties here!
     */

    public $title;
    public $shipping_date = "";
    public $pickup_date = "";
    public $start_date = "";
    public $end_date = "";
    public $exp_return_date = "";

    public $items;
    public $stockProducts = []; 


    public function mount() {
        $this->stockProducts = [
            ['id' => '', 'quantity' => 1]
        ];
    }
   
    /**
     * Add product Item. 
     * @return object
     */
    public function addProduct()
    {       
        $this->stockProducts[] = ['id' => '', 'quantity' => 1];
    }
	
	/**
     * check product linked. 
     * @return object
     */
	public function checkProduct()
    {
		$product = end($this->stockProducts);
        $linkeditem = Linkeditem::where('item_id', $product['id'])->get();
		foreach($linkeditem as $linked){
			$this->stockProducts[] = ['id' => $linked->linked_item_id, 'quantity' => 1];
		}
	}
	
	
    /**
     * Remove product item.
     * @return value 
     */
    public function removeProduct($index)
    {
        unset($this->stockProducts[$index]);
        $this->stockProducts = array_values($this->stockProducts);
    }
    /**
     * The validation rules
     *
     * @return void
     */
    public function rules()
    {
        return [      
            'title' =>'required',
            'pickup_date' =>'required|date',
            'shipping_date' => 'required|date|after_or_equal:pickup_date',
            'start_date' => 'required|date|after_or_equal:shipping_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exp_return_date' => 'required|date|after_or_equal:end_date',
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
        $data = Project::find($this->modelId);
        // Assign the variables here
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
            'title' => $this->title,
            'pickup_date' => $this->pickup_date,
            'shipping_date' => $this->shipping_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'expected_return_date' => $this->exp_return_date,
            'status' => 1,
            'user_id' => auth()->user()->id,
            'organisation_id' => auth()->user()->organization_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ];
    }


    /**
     * The create function.
     *
     * @return void
     */
    public function create()
    {
		#dd($this->modelData());
        $this->resetErrorBag();
        $this->validate();
        $perform = Project::create($this->modelData());

        foreach($this->stockProducts as $stock){
            $perform->items()->create([
                'quantity' =>  $stock['quantity'],
                'item_id'  => $stock['id'],
                'user_id'  => auth()->user()->id,
                'project_id' => $perform->id
            ])->user()->associate(auth()->user()->id)->save();
        }
        $this->modalFormVisible = false;
        $this->reset();
        redirect()->to('/projects');
    }

    /**
     * The read function.
     *
     * @return void
     */
    public function read()
    {
        return Project::with('items')->paginate(5);
    }

    /**
     * The update function
     *
     * @return void
     */
    public function update()
    {
        $this->validate();
        Project::find($this->modelId)->update($this->modelData());
		
		$perform = Project::find($this->modelId);
		foreach($this->stockProducts as $stock){
            $perform->items()->create([
                'quantity' =>  $stock['quantity'],
                'itemable_id'  => $stock['id'],
                'user_id'  => auth()->user()->id,
                'project_id' => $perform->id
            ])->user()->associate(auth()->user()->id)->save();
        }
		
        $this->modalFormVisible = false;
    }

    /**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        Project::destroy($this->modelId);
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
        return view('livewire.project-component', [
            'data' => $this->read(),
        ]);
    }
}