<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Subitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pictureurl',
        'serialnumber',
        'quantity',
        'date_of_purchase',
        'warranty_expiry_period',
        'receipt_url',
        'barcode_no',
        'barcode_url',
        'condition',
        'status',
        'notes',
        'code',
        'item_id',
        'organisation_id',
        'created_by',
        'updated_by'
    ];
    
    public static function getConditions(){
        return ['excellent'=>'Excellent','good'=>'Good','ok'=>'Ok','needrepair'=>'Needrepair','broken'=>'Broken'];
    }
    
    public static function getStatus(){
        return ['available'=>'Available','awaitingbooking'=>'Awaiting booking','onjob'=>'On Job','intransit'=>'In Transit'];
    }
    
     public static function getlinkedsubitems(){
        return Subitem::all()->where('organisation_id', '=', Auth::user()->organization_id);
    }
    
}
