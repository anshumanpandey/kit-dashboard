<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'project_id', 'sub_item_barcode', 'checkout_status_id', 'created_by', 'updated_by'
    ];
}
