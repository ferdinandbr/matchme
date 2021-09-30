<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    protected $table = 'user_group';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'group_id'
    ];

}
