<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubitemBarcode extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'sub_item_id', 'barcode_no', 'barcode_url'
    ];
	
	
	
}
