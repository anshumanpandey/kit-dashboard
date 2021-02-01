<?php

namespace App\Http\Livewire;

use App\Models\SubItem;
use App\Models\Linkedsubitem;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image as Image;
use Milon\Barcode\DNS1D;
use Auth;

class SubitemComponent extends Component {

    use WithPagination;
    use WithFileUploads;

    public $modalFormVisible;
    public $modalConfirmDeleteVisible;
    public $modelId;
    public $receipt, $picture, $item_id;
    public $serialnumber, $condition, $status, $notes, $pictureurl, $date_of_purchase, $warranty_expiry_period, $receipt_url, $barcode_no, $barcode_url, $organisation_id, $code;
    public $quantity;
    public $linkedsubitems = [];

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
            'serialnumber' => 'required',
            'pictureurl' => 'required|image',
            'date_of_purchase' => 'required',
            'warranty_expiry_period' => 'required',
            'quantity' => 'required',
            'receipt_url' => 'required|image',
            'barcode_no' => 'required',
            'barcode_url' => 'required',
            'condition' => 'required',
            'status' => 'required',
            'item_id' => 'required',
            'linkedsubitems' => 'required',
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
        $this->date_of_purchase = $data->date_of_purchase;
        $this->warranty_expiry_period = $data->warranty_expiry_period;
        $this->code = $data->code;
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

        
        
        $dns = new DNS1D();
        $barcode = time() . rand();
        $barcodeImage = $dns->getBarcodePNG($barcode, 'C128', 1, 33, array(1, 1, 1), true);
        $path = public_path('assets/barcodes/' . $barcode . '.jpg');
        Image::make(file_get_contents('data:image/jpeg;base64, ' . $barcodeImage))->save($path);
        
        
        if ($this->pictureurl) {
            $picture =time() . rand().'.jpg';
            $this->pictureurl->storeAs('public/photos', $picture);
        }else{
            $picture = "";
        }
            
//        dd($picture);
        
        if ($this->receipt_url) {
            $receipt_url =time() . rand().'.jpg';
            $this->receipt_url->storeAs('public/photos', $receipt_url);
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
     * The create function.
     *
     * @return void
     */
    public function create() {

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

        $this->modalFormVisible = false;
        $this->reset();

        redirect()->to('/sub-items');
    }

    /**
     * The read function.
     *
     * @return void
     */
    public function read() {
        return SubItem::latest()->where('organisation_id', '=', Auth::user()->organization_id)->paginate(5);
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

    public function mount() {
        
    }

    public function render() {

        return view('livewire.subitem-component', [
            'data' => $this->read(),
        ]);
    }

}
