<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\ProjectBarcode;
use Auth;

class ProjectsController extends Controller
{
    public function index(Request $request)
	{
		$projects = Project::with('withSubitems', 'projectBarcodes')
			->where('organisation_id', Auth::user()->organization_id)
			->get();		
		$date = date('Y-m-d');
		
		if($projects){
			foreach($projects as $project){
				$project->project_status = 'Live';
				if($project->pickup_date > $date){
					$project->project_status = 'In Future';
				}
				if($project->expected_return_date < $date){
					$project->project_status = 'Archived';
				}
				if(($project->pickup_date >= $date) && ($project->expected_return_date <= $date)){
					$project->project_status = 'Live';
				}
				
				foreach($project->withSubitems as $items){
					$items->pictureurl = asset('storage/photos/'.$items->pictureurl);
					$items->receipt_url = asset('storage/photos/'.$items->receipt_url);
					
					$items->projectBarcodes = ProjectBarcode::where('sub_item_id', $items->id)->where('project_id', $project->id)->get();
					
				}
				
				
			}
			return response($projects, 200);
		}
		return response(['message'=>'Data not found', "code"=>422], 422);
	}
}
