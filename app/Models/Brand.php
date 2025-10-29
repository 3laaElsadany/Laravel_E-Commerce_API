<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    protected $table = 'brands';
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];

    
}
