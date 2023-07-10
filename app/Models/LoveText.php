<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoveText extends Model
{
    use HasFactory;
    protected $table = 'love_texts';

    protected $fillable = [
        'english',
        'vietnamese',
        'Note',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
