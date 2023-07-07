<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WordLookupHistory extends Model
{
    use HasFactory;
    protected $table = 'word_lookup_histories';

    protected $fillable = [
        'english',
        'pronunciations',
        'vietnamese',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
