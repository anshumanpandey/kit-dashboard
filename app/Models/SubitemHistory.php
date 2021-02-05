<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubitemHistory extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'sub_item_id', 'notificationtext', 'created_by', 'updated_by'
    ];
}
