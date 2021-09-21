<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $table = 'invitation';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'hash',
        'valid'
    ];
}
