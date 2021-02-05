<?php

namespace App\Http\Livewire;

use App\Models\SubItem;
use App\Models\Linkedsubitem;
use App\Models\SubitemBarcode;
use Auth;

use Livewire\Component;
use Livewire\WithPagination;

use Intervention\Image\Facades\Image as Image;
use Milon\Barcode\DNS1D;
use Livewire\WithFileUploads;

class SubitemsAction extends Component
{
	use WithPagination;
	use WithFileUploads;
	
	public $perPage = 10;
    public $sortField='serialnumber';
    public $sortAsc = true;
    public $search = '';
	
	/**
     * Put your custom public properties here!
     */
	public $modalFormVisible;
    public $modalConfirmDeleteVisible;
	public $modalBarcodeVisible;
	public $modalListItemsShowModal;
	
    public $modelId;
    public $receipt, $picture, $item_id;
    public $serialnumber, $condition, $notes, $pictureurl, $date_of_purchase, $warranty_expiry_period, $receipt_url, $barcode_no, $barcode_url, $organisation_id, $code;
    public $quantity;
    public $pictureurlImg;
    public $receipt_urlImg;
    public $status = 'available';
    public $linkedsubitems = [];
    public $allBarcodes = [];
	
	/**
     * Put your custom public properties here!
     */

    /**
     * The validation rules
     *
     * @return void
     */
    public function rules() {
        if($this->modelId){
			return [
				'serialnumber' => 'required',
				'date_of_purchase' => 'required',
				'warranty_expiry_period' => 'required',
				'quantity' => 'required',
				'condition' => 'required',
				'item_id' => 'required',
			];
		}
		
		return [
            'serialnumber' => 'required',
            'pictureurl' => 'required|image',
            'date_of_purchase' => 'required',
            'warranty_expiry_period' => 'required',
            'quantity' => 'required',
            'receipt_url' => 'required|image',
            'condition' => 'required',
            'item_id' => 'required',
        ];
    }
	
	/**
     * Loads the model data
     * of this component.
     *
     * @return void
     */
    public function loadModel() {
        $data = SubItem::find($this->modelId);
        // Assign the variables here

        $this->serialnumber = $data->serialnumber;
        $this->date_of_purchase = $data->date_of_purchase;
        $this->warranty_expiry_period = $data->warranty_expiry_period;
        $this->condition = $data->condition;
        $this->status = $data->status;
        $this->notes = $data->notes;
        $this->quantity = $data->quantity;
        $this->linkedsubitems = $data->linkedsubitems;
        $this->pictureurlImg = $data->pictureurl;
        $this->receipt_urlImg = $data->receipt_url;
        //$this->code = $data->code;
        $this->receipt = $data->receipt;
        $this->item_id = $data->item_id;
    }

    /**
     * The data for the model mapped
     * in this component.
     *
     * @return void
     */
    public function modelData() {
        if ($this->pictureurl) {
            $picture =time() . rand().'.jpg';
            $this->pictureurl->storeAs('public/photos', $picture);
        }else{
            $picture = "";
        }
        
        if ($this->receipt_url) {
            $receipt_url =time() . rand().'.jpg';
            $this->receipt_url->storeAs('public/photos', $receipt_url);
        }else{
            $receipt_url = "";
        }
		
		$available_qty = $this->quantity;
		
		if($this->modelId){
			$data = SubItem::find($this->modelId);
			if($picture==''){
				$picture = $data->pictureurl;
			}
			if($receipt_url==''){
				$receipt_url = $data->receipt_url;
			}
			
			$available_qty = $data->available_qty;
			if($this->quantity>$data->quantity){
				$diff = $this->quantity - $data->quantity;
				$available_qty = $data->available_qty + $diff;
			}
			if($this->quantity<$data->quantity){
				$diff = $data->quantity - $this->quantity;
				$available_qty = $data->available_qty - $diff;
			}
		}

        return [
            'serialnumber' => $this->serialnumber,
            'pictureurl' => $picture,
            'date_of_purchase' => $this->date_of_purchase,
            'warranty_expiry_period' => $this->warranty_expiry_period,
            'quantity' => $this->quantity,
            'available_qty' => $available_qty,
            'receipt_url' => $receipt_url,
            'condition' => $this->condition,
            'status' => $this->status,
            'notes' => $this->notes,
            'item_id' => $this->item_id,
            'organisation_id' => auth()->user()->organization_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ];
    }

    /**
     * The create function.
     *
     * @return void
     */
    public function create() {
		$this->validate();
        $subItem = SubItem::create($this->modelData());
        if ($subItem) {
            $saveLinked = [];
            foreach ($this->linkedsubitems as $key => $linkedItems) {
                $saveLinked['sub_item_id'] = $subItem->id;
                $saveLinked['linked_sub_item_id'] = $linkedItems;
                $saveLinked['created_by'] = auth()->user()->id;
                $saveLinked['updated_by'] = auth()->user()->id;

                Linkedsubitem::create($saveLinked);
            }
        }		
		
		if($subItem->quantity>0){
			for($i=1; $i<=$subItem->quantity; $i++){
				$dns = new DNS1D();
				$barcode = time() . $i . rand();
				$barcodeImage = $dns->getBarcodePNG($barcode, 'C128', 1, 33, array(1, 1, 1), true);
				$path = public_path('assets/barcodes/' . $barcode . '.jpg');
				Image::make(file_get_contents('data:image/jpeg;base64, ' . $barcodeImage))->save($path);
				
				//save barcode
				$storeBarcode = new SubitemBarcode;
				$storeBarcode->sub_item_id = $subItem->id;
				$storeBarcode->barcode_no = $barcode;
				$storeBarcode->barcode_url = $barcode . ".jpg";
				$storeBarcode->save();
			}
		}
		
        $this->modalFormVisible = false;
        $this->reset();

        redirect()->to('/sub-items');
    }
	
	/**
     * The update function
     *
     * @return void
     */
    public function update() {
        $this->validate();
        $subItem = SubItem::find($this->modelId)->update($this->modelData());
		
		$subItem = SubItem::find($this->modelId);
		if ($subItem) {
            foreach ($this->linkedsubitems as $key => $linkedItems) {
			
				$check = Linkedsubitem::where('sub_item_id', $subItem->id)
					->where('linked_sub_item_id', $linkedItems)->first();
				$linked = new Linkedsubitem;
				if(@$check){
					$linked->id = $check->id;
					$linked->exists = true;
				}
				else {
					$linked->sub_item_id = $subItem->id;
				}
				
				$linked->linked_sub_item_id = $linkedItems;
				$linked->created_by = auth()->user()->id;
                $linked->updated_by = auth()->user()->id;
				$linked->save();
            }
        }
		
		$quantity = $subItem->quantity;
		$count = SubitemBarcode::where('sub_item_id', $subItem->id)->count();
		
		$quantity = $quantity - $count;
		if($quantity>0){
			for($i=1; $i<=$quantity; $i++){
				$dns = new DNS1D();
				$barcode = time() . $i . rand();
				$barcodeImage = $dns->getBarcodePNG($barcode, 'C128', 1, 33, array(1, 1, 1), true);
				$path = public_path('assets/barcodes/' . $barcode . '.jpg');
				Image::make(file_get_contents('data:image/jpeg;base64, ' . $barcodeImage))->save($path);
				
				//save barcode
				$storeBarcode = new SubitemBarcode;
				$storeBarcode->sub_item_id = $subItem->id;
				$storeBarcode->barcode_no = $barcode;
				$storeBarcode->barcode_url = $barcode . ".jpg";
				$storeBarcode->save();
			}
		}
		
		
        $this->modalFormVisible = false;
		redirect()->to('/sub-items');
    }

    /**
     * The delete function.
     *
     * @return void
     */
    public function delete() {
        SubItem::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
     * Shows the create modal
     *
     * @return void
     */
    public function createShowModal() {
		$this->pictureurl = '';
		$this->receipt_url = '';
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible = true;
    }
	
	public function barcodeShowModal($id) {
        $this->modalBarcodeVisible = true;
        $this->allBarcodes = SubitemBarcode::where('sub_item_id', $id)->get();
    }

    /**
     * Shows the form modal
     * in update mode.
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id) {
		$this->pictureurl = '';
		$this->receipt_url = '';
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
	
	public function listItemsShowModal($id) {
        $this->modelId = $id;
		$this->allBarcodes = SubitemBarcode::where('sub_item_id', $id)->get();
        $this->modalListItemsShowModal = true;
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
        return view('livewire.subitems-action', [
            'subitems' => SubItem::where('organisation_id', '=', Auth::user()->organization_id)
				->where(function ($query) use ($search) {
					$query->where('serialnumber', 'like', '%'.$search.'%')
					->orWhere('quantity', 'like', '%'.$search.'%')
					->orWhere('date_of_purchase', 'like', '%'.$search.'%')
					->orWhere('warranty_expiry_period', 'like', '%'.$search.'%')
					->orWhere('status', 'like', '%'.$search.'%')
					->orWhere('condition', 'like', '%'.$search.'%');
				})
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ]);
    }
}
