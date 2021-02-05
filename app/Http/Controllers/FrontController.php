<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Artisan;

class FrontController extends Controller
{
    //...
	public function cron(){
		Artisan::call('return:cron');
	}
}
