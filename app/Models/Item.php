<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Item extends Model {

    use HasFactory;

    protected $fillable = [
        'name', 'cable_length', 'make', 'model', 'status', 'organisation_id', 'created_by', 'updated_by', 'item_type_id'
    ];

    public static function getlinkeditems(){
        return Item::all()->where('organisation_id', '=', Auth::user()->organization_id);
    }
    
    public function itemtypes() {
        return $this->hasMany(ItemType::class);
    }

	public function linkeditems(){
        return $this->hasMany(Linkeditem::class, 'item_id');
    }
	
	public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%')
                ->orWhere('cable_length', 'like', '%'.$query.'%')
                ->orWhere('make', 'like', '%'.$query.'%')
                ->orWhere('model', 'like', '%'.$query.'%');
    }
	
}
