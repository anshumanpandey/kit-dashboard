<?php

namespace App\Http\Livewire;

use App\Models\SubItem;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Hash;
use Auth;

use Intervention\Image\Facades\Image as Image;
use Milon\Barcode\DNS1D;
use Livewire\WithFileUploads;


class SubitemTable extends LivewireDatatable {

    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modalBarcodeVisible;
    public $modelId;
    public $receipt, $picture, $item_id;
    public $serialnumber, $condition, $status, $notes, $pictureurl, $date_of_purchase, $warranty_expiry_period, $receipt_url, $barcode_no, $barcode_url, $organisation_id, $code;
    public $quantity;
    public $linkedsubitems = [];

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
    public function rules() {
        return [
            'serialnumber' => 'required',
            'date_of_purchase' => 'required',
            'warranty_expiry_period' => 'required',
            'quantity' => 'required',
            'condition' => 'required',
            'status' => 'required',
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
        $this->pictureurl = $data->pictureurl;
        $this->receipt_url = $data->receipt_url;
        $this->barcode_url = $data->barcode_url;
        $this->warranty_expiry_period = $data->warranty_expiry_period;
        $this->code = $data->code;
        $this->receipt = $data->receipt;
        $this->picture = $data->picture;
        $this->item_id = $data->item_id;
    }

    public function builder() {
        return SubItem::latest()->where('organisation_id', '=', Auth::user()->organization_id);
    }

    /**
     * The data for the model mapped
     * in this component.
     *
     * @return void
     */
    public function modelData() {
        
        $dns = new DNS1D();
        $barcode = time() . rand();
        $barcodeImage = $dns->getBarcodePNG($barcode, 'C128', 1, 33, array(1, 1, 1), true);
        $path = public_path('assets/barcodes/' . $barcode . '.jpg');
        Image::make(file_get_contents('data:image/jpeg;base64, ' . $barcodeImage))->save($path);
        
        
        if ($this->picture) {
            $picture =time() . rand().'.jpg';
            $this->picture->storeAs('public/photos', $picture);
        }else{
            $picture = "";
        }
            
//        dd($picture);
        
        if ($this->receipt) {
            $receipt_url =time() . rand().'.jpg';
            $this->receipt->storeAs('public/photos', $receipt_url);
        }else{
            $receipt_url = "";
        }

        return [
            'serialnumber' => $this->serialnumber,
            'pictureurl' => $picture,
            'date_of_purchase' => $this->date_of_purchase,
            'warranty_expiry_period' => $this->warranty_expiry_period,
            'quantity' => $this->quantity,
            'receipt_url' => $receipt_url,
            'barcode_no' => $this->barcode_no,
            'barcode_url' => $barcode . ".jpg",
            'code' => $barcode,
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
     * The delete function.
     *
     * @return void
     */
    public function deleteSubitem() {
        SubItem::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
		redirect()->to('/sub-items');
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
        SubItem::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
		redirect()->to('/sub-items');
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
	
	
	
	public function barcodeShowModal($id) {
        $this->modalBarcodeVisible = true;
        $this->modelId = $id;
        $this->loadModel();
    }
	

    public function columns() {
        return [
            Column::name('code')->searchable(),
            Column::name('serialnumber')->searchable(),
            DateColumn::name('date_of_purchase'),
            DateColumn::name('warranty_expiry_period'),
            NumberColumn::name('quantity')->searchable(),
            Column::name('condition')->searchable(),
            Column::name('status')->searchable(),
            DateColumn::name('created_at'),
            Column::callback(['id'], function ($id) {
              return view('Subitems-actions', ['id' => $id, 'modelId' => $id]);
            })
        ];
    }

}
