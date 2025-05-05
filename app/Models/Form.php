<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class form extends Model
{
   use HasFactory;

   protected $guarded = ['id', 'created_at', 'updated_at'];

   public $timestamps = true;

   public function fields()
   { 
    return $this->hasMany(FormField::class); 
   }

    public function answers() 
    {
        return $this->hasMany(FormAnswer::class); 
    }
}
