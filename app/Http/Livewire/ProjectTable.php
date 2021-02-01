<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\Item;
use App\Models\Linkeditem;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Hash;
use Auth;

class ProjectTable extends LivewireDatatable
{
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible;
    public $modelId;
    public $title;
    public $pickup_date;   
    public $shipping_date;
    public $start_date;
    public $end_date;
    public $exp_return_date;   
    public $ProjectItem = [];
    public $stockProducts = [];
	public $items;
	
	
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
        if(@$product){
			$linkeditem = Linkeditem::where('item_id', $product['id'])->get();
			if(@$linkeditem){
				foreach($linkeditem as $linked){
					$this->stockProducts[] = ['id' => $linked->linked_item_id, 'quantity' => 1];
				}
			}
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
	
	
	
	
	
    public function builder()
    {
        $this->stockProducts = array_map(function ($projectItem) {
            $item = Item::find($projectItem["itemable_id"]);
			if(@$item){
				$item->quantity = $projectItem["quantity"];
			}
            return $item;
        }, ProjectItem::latest()->get()->toArray());
        return Project::latest();
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
    public function deleteProject()
    {
        Project::destroy($this->modelId);
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
        $data = Project::find($this->modelId);
        // Assign the variables here
		
		$projectitem = ProjectItem::where('project_id', $data->id)->get();

        $this->title = $data->title;
        $this->pickup_date = $data->pickup_date;
        $this->shipping_date = $data->shipping_date;
        $this->start_date = $data->start_date;
        $this->end_date = $data->end_date;
        $this->exp_return_date = $data->expected_return_date;
		
		
		$this->ProjectItem = $projectitem ;
		
		// if($projectitem){
			// foreach($projectitem as $k => $item){
				// if($k==0){
					// $this->stockProducts = [
						// ['id' => $item->item_id, 'quantity' => $item->quantity]
					// ];
				// }
				// else {
					// $this->stockProducts[] = ['id' => $item->item_id, 'quantity' => $item->quantity];
				// }
			// }
		// }
		// else {
			// $this->stockProducts = [
				// ['id' => '', 'quantity' => 1]
			// ];
		// }
		
		
		//
		// $this->stockProducts = [
            // ['id' => '', 'quantity' => 1]
        // ];
		//dd($this->stockProducts);
    }


    public function columns()
    {
        return [
            Column::name('title')->searchable(),

            DateColumn::name('created_at'),

            Column::callback(['id'], function ($id) {
                return view('ProjectTable-actions', ['id' => $id, 'modelId' => $id ]);
            })
        ];
    }
}