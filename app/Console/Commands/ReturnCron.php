<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\SubItem;

use Illuminate\Console\Command;
use Mail;

class ReturnCron extends Command
{
	
	/**
	* The name and signature of the console command.
	*
	* @var string
	*/
	protected $signature = 'return:cron';

	/**
	* The console command description.
	*
	* @var string
	*/
	protected $description = 'Project Return';
	
	/**
	* Execute the console command.
	*
	* @return mixed
	*/
	public function handle(){
		
		$date = '2021-02-11';
		$projects = Project::whereDate('expected_return_date', $date)->where('cron', '0')->get();
		//dd($projects);
		foreach($projects as $project){
			$projectitem = ProjectItem::where('project_id', $project->id)->get();
			
			foreach($projectitem as $item){
				$subitems = SubItem::find($item->item_id);
				$subitems->quantity = $subitems->quantity + $item->quantity;
				$subitems->save();
			}
			
			$project->cron = '1';
			$project->save();
		}
		
		echo 'Done';
		
	}
	
	
}