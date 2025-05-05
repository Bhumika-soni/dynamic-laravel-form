<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormAnswer extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = true;
    
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formField()
    {
        return $this->belongsTo(FormField::class);
    }
    
    /**
     * Get the answer value with special handling for file paths
     */
    public function getFormattedAnswerAttribute()
    {
        if ($this->formField && $this->formField->type === 'file' && $this->answer) {
            return asset('storage/' . $this->answer);
        }
        
        return $this->answer;
    }
}
