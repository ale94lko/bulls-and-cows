<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'user',
        'age',
        'max_time',
        'secret_number',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $bytes = random_bytes(24);
            $model->identifier = bin2hex($bytes);
        });
    }
}
