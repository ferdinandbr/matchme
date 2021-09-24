<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $table = 'interest';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'visible'
    ];

    public function user()
    {

        return $this->belongsToMany(
            User::class,
            'user_interest',
            'interest_id',
            'user_id'
        );
    }
}
