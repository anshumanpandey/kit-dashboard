<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

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
	
	public function withSubitems()
    {
		return $this->belongsToMany(SubItem::class, ProjectItem::class, 'project_id', 'item_id')->withPivot('quantity');
	}

    /**
     * static function for Modal popup 
     */
    public static function getItems()
    {
        return Item::all()->where('organisation_id', '=', Auth::user()->organization_id);
    }
	
	public static function getSubItems()
    {
		return SubItem::all()->where('organisation_id', '=', Auth::user()->organization_id);
    }
	
	public function projectBarcodes()
    {
		return $this->hasMany(ProjectBarcode::class, 'project_id');
    }
	
	
	
	public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('title', 'like', '%'.$query.'%')
                ->orWhere('pickup_date', 'like', '%'.$query.'%')
                ->orWhere('shipping_date', 'like', '%'.$query.'%')
                ->orWhere('start_date', 'like', '%'.$query.'%')
                ->orWhere('end_date', 'like', '%'.$query.'%')
                ->orWhere('expected_return_date', 'like', '%'.$query.'%')
                ->orWhere('status', 'like', '%'.$query.'%');
    }
	
	// public function scopeFilter($query, array $filters)
    // {
        // $query->when($filters['search'] ?? null, function ($query, $search) {
            // $query->where(function ($query) use ($search) {
                // $query->where('first_name', 'like', '%'.$search.'%')
                    // ->orWhere('last_name', 'like', '%'.$search.'%')
                    // ->orWhere('email', 'like', '%'.$search.'%');
            // });
        // })->when($filters['role'] ?? null, function ($query, $role) {
            // $query->whereRole($role);
        // })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            // if ($trashed === 'with') {
                // $query->withTrashed();
            // } elseif ($trashed === 'only') {
                // $query->onlyTrashed();
            // }
        // });
		
		
			// if($value){
				// $date = date('Y-m-d');
				// if($value=='future'){
					// $query->whereDate('pickup_date', '>', $date);
				// }
				// if($value=='live'){
					// $query->whereDate('pickup_date', '>=', $date);
					// $query->whereDate('expected_return_date', '<=', $date);
				// }
				
				// if($value=='archived'){
					// $query->whereDate('expected_return_date', '<', $date);
				// }
			// };
    // }
	
	
}
