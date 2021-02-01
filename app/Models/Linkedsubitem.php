<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Linkedsubitem extends Model {

    use HasFactory;

    protected $fillable = [
        'sub_item_id', 'linked_sub_item_id', 'created_by', 'updated_by'
    ];

}
