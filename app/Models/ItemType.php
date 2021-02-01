<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model {

    use HasFactory;

    public static function getItemTypes() {
        
        
        return ItemType::all();
    }
    
    public function items()
    {
        return $this->belongsTo(Item::class,'item_type_id', 'id');
    }
}
