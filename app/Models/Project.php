<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'pickup_date',
        'shipping_date',
        'start_date',
        'end_date',
        'expected_return_date',
        'status',
        'organisation_id',
        'created_by',
        'updated_by'
    ];

    /**
     * @return relationship 
     */
    public function items()
    {
        return $this->morphMany(ProjectItem::class, 'itemable');
    }

    /**
     * static function for Modal popup 
     */
    public static function getItems()
    {
         return Item::all();
    }
}
