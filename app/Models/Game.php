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
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $bytes = random_bytes(24);
            $model->identifier = bin2hex($bytes);
            $model->secret_number = $model->genSecretNumber();
        });
    }

    public function genSecretNumber():int
    {
        $numbers = [];

        do {
            $randomDigit = count($numbers) === 0
                ? mt_rand(1, 9) : mt_rand(0, 9);
            if (!in_array($randomDigit, $numbers)) {
                $numbers[] = $randomDigit;
            }
        } while (count($numbers) < 4);

        return (int) implode('', $numbers);
    }
}
