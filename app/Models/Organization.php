<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','created_by','updated_by'
    ];

    /**
     * 
     */
    public function user() {
        return $this->hasMany(User::class);
    }
	
	public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%')
                ->orWhere('created_by', 'like', '%'.$query.'%');
    }
	
}
