<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\ProjectHistory;
use App\Models\ProjectBarcode;
use App\Models\SubitemBarcode;
use Auth;

class CheckoutController extends Controller
{
    //store	
	public function store(Request $request)
	{
		$request->validate([
			'project_id' => 'required',
			'barcode_number' => 'required',
			'checkout_status_id' => 'required'
		]);
		
		if($request->checkout_status_id=='1'){
			$check = ProjectBarcode::where('project_id', $request->project_id)
				->where('barcode', $request->barcode_number)
				->where('status', 'checkout')->first();
			if(!$check){
				return response(['message'=>"Barcode did't find for checkin"], 404);
			}
		}
		
		
		$store = new Checkout;
		$store->project_id = $request->project_id;
		$store->sub_item_barcode = $request->barcode_number;
		$store->checkout_status_id = $request->checkout_status_id;
		$store->created_by = Auth::user()->id;
		$store->updated_by = Auth::user()->id;
		$store->save();
		
		$type = 'Checkout';
		$status = 'checkout';
		if($request->checkout_status_id=='1'){
			$type = 'Checkin';
			$status = 'checkin';
		}
		
		$message = $type.' completed for barcode '.$request->barcode_number;
		//...
		$history = new ProjectHistory;
		$history->project_id = $request->project_id;
		$history->barcode = $request->barcode_number;
		$history->notificationtext = $message;
		$history->created_by = Auth::user()->id;
		$history->updated_by = Auth::user()->id;
		$history->save();		
		
		//...
		$barcode_id = SubitemBarcode::where('barcode_no', $request->barcode_number)->first();
		$checkIf = ProjectBarcode::where('project_id', $request->project_id)
			->where('barcode_id', $barcode_id->id)
			->where('barcode', $request->barcode_number)->first();
		
		$pbar = new ProjectBarcode;
		if(@$checkIf){
			$pbar->id = $barcode_id->id;
			$pbar->exists = true;
		}
		$pbar->sub_item_id = $barcode_id->sub_item_id;
		$pbar->project_id = $request->project_id;
		$pbar->barcode_id = $barcode_id->id;
		$pbar->barcode = $request->barcode_number;
		$pbar->created_by = Auth::user()->id;
		$pbar->updated_by = Auth::user()->id;
		$pbar->status = $status;
		$pbar->save();
		
		return response(['message'=>$message], 200);
	}
}





