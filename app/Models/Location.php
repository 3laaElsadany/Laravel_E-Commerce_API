<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $fillable = ['user_id', 'street', 'building', 'area'];
    protected $hidden = ['created_at', 'updated_at'];

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
