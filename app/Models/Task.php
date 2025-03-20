<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Define which fields can be mass-assigned
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date'
    ];

      // Define attribute casting
      protected $casts = [
        'due_date' => 'date',
    ];
}
