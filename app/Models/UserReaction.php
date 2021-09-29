<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReaction extends Model
{
    use HasFactory;

    protected $table = 'user_reaction';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_react_id',
        'user_reacted_id',
        'reaction_id'
    ];  
}
