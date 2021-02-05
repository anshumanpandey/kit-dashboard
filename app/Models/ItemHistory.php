<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'item_id', 'notificationtext', 'created_by', 'updated_by'
    ];
}
