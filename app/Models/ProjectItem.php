<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    /**
     * @return relationship Many
     */
    public function itemable()
    {
        return $this->morphTo('itemable');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
	
	public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
