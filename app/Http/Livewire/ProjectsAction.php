<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use Auth;

use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\Item;
use App\Models\Linkeditem;
use App\Models\SubItem;
use App\Models\ProjectHistory;

class ProjectsAction extends Component
{
	use WithPagination;
	
	public $perPage = 10;
    public $sortField='title';
    public $sortAsc = true;
    public $search = '';
    public $search_type = '';
	
	public $modalFormVisible = false;
    public $copyProject = false;
    public $modalHistoryVisible = false;
    public $modalConfirmDeleteVisible;
	public $modelId;
	
	public $title;
    public $shipping_date = "";
    public $pickup_date = "";
    public $start_date = "";
    public $end_date = "";
    public $exp_return_date = "";

    public $items;
    public $stockProducts = [];
    public $history = [];


    public function mount() {
        $this->stockProducts = [
            ['id' => '', 'quantity' => 1, 'avl_quantity' => '']
        ];
    }
	
	/**
     * Add product Item. 
     * @return object
     */
    public function addProduct()
    {       
        $this->stockProducts[] = ['id' => '', 'quantity' => 1, 'avl_quantity' => ''];
    }
	
	/**
     * check product linked. 
     * @return object
     */
	public function checkProduct($index)
    {
		$product = $this->stockProducts[$index];
		$subitem = SubItem::find($product['id']);
		$this->stockProducts[$index] = ['id' =>$product['id'], 'quantity' => $product['quantity'], 'avl_quantity' => $subitem->available_qty];
		//dd($this->stockProducts);
        $linkeditem = Linkeditem::where('item_id', $product['id'])->get();
		foreach($linkeditem as $linked){
			$this->stockProducts[] = ['id' => $linked->linked_item_id, 'quantity' => 1, 'avl_quantity' => $linked->available_qty];
		}
	}
	
	
    /**
     * Remove product item.
     * @return value 
     */
    public function removeProduct($index, $productId)
    {
		ProjectItem::where('project_id', $this->modelId)
				->where('item_id', $productId)
				->delete();
				
        unset($this->stockProducts[$index]);
        $this->stockProducts = array_values($this->stockProducts);
    }
	
	public function checkQty($index){
		$stock = $this->stockProducts[$index];
		//dd($stock['']);
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
		$this->stockProducts = [];
        $data = Project::find($this->modelId);
        // Assign the variables here
		
		$projectitem = ProjectItem::where('project_id', $data->id)->get();

		if($this->copyProject){
			$this->title = '';
		}
		else {
			$this->title = $data->title;
		}
		
        $this->pickup_date = $data->pickup_date;
        $this->shipping_date = $data->shipping_date;
        $this->start_date = $data->start_date;
        $this->end_date = $data->end_date;
        $this->exp_return_date = $data->expected_return_date;		
		
		$this->ProjectItem = $projectitem ;
		
		if($projectitem){
			foreach($projectitem as $k => $item){
				$subitem = SubItem::find($item->item_id);
				if($k==0){
					$this->stockProducts = [
						['id' => $item->item_id, 'quantity' => $item->quantity, 'avl_quantity' => $subitem->available_qty]
					];
				}
				if($k>0){
					$this->stockProducts[] = ['id' => $item->item_id, 'quantity' => $item->quantity, 'avl_quantity' => $subitem->available_qty];
				}
			}
		}
		
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
     * Shows the create modal
     *
     * @return void
     */
    public function createCopyModal($id)
    {
        $this->modalFormVisible = true;
		$this->modelId = $id;
        $this->copyProject = true;
        $this->loadModel();
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

			//----
			$subitems = SubItem::find($stock['id']);			
			$subitems->quantity = $subitems->quantity - $stock['quantity'];
			$subitems->save();            
        }
        $this->modalFormVisible = false;
        $this->reset();
        redirect()->to('/projects');
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
        $this->modalFormVisible = true;
        $this->modelId = $id;
        $this->copyProject = false;
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
			$projectitem = ProjectItem::where('project_id', $this->modelId)
				->where('item_id', $stock['id'])
				->first();
			if(@$projectitem){
				$projectitem->quantity = $stock['quantity'];
				$projectitem->save();
			}
			else {
				$perform->items()->create([
					'quantity' =>  $stock['quantity'],
					'item_id'  => $stock['id'],
					'user_id'  => auth()->user()->id,
					'project_id' => $perform->id
				])->user()->associate(auth()->user()->id)->save();
			}
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
	
	public function historyModal($id)
    {
        $this->modelId = $id;
		$this->history = ProjectHistory::where('project_id', $id)->get();
        $this->modalHistoryVisible = true;
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
		$search_type = $this->search_type;
		
        return view('livewire.projects-action', [
            'projects' => Project::with('items')
				->where(function ($query) use ($search) {
					$query->where('title', 'like', '%'.$search.'%')
					->orWhere('pickup_date', 'like', '%'.$search.'%')
					->orWhere('shipping_date', 'like', '%'.$search.'%')
					->orWhere('start_date', 'like', '%'.$search.'%')
					->orWhere('end_date', 'like', '%'.$search.'%')
					->orWhere('expected_return_date', 'like', '%'.$search.'%')
					->orWhere('status', 'like', '%'.$search.'%');
				})
				->where(function ($query) use ($search_type) {					
					if($search_type){
						$date = date('Y-m-d');
						if($search_type=='future'){
							$query->whereDate('pickup_date', '>', $date);
						}
						if($search_type=='live'){
							$query->whereDate('pickup_date', '<=', $date);
							$query->whereDate('expected_return_date', '>=', $date);
						}
						if($search_type=='archived'){
							$query->whereDate('expected_return_date', '<', $date);
						}
					};			
				})
				->where('organisation_id', '=', Auth::user()->organization_id)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ]);
    }
	
}
