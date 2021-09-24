<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    use HasFactory;

    protected $table = 'user_interest';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'interest_id',
    ];  

}
