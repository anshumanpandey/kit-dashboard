<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBarcode extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'sub_item_id',
        'project_id',
        'barcode_id',
        'created_by',
        'updated_by',
        'barcode',
        'status',
    ];
}
