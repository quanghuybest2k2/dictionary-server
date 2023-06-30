<?php

namespace App\Models;

use App\Models\Word;
use App\Models\WordType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Means extends Model
{
    use HasFactory;
    protected $table = 'means';

    protected $fillable = [
        'word_id',
        'word_type_id',
        'means',
        'description',
        'example',
    ];
    // protected $with = ['Word', 'WordType'];
    public function Word()
    {
        return $this->belongsTo(Word::class, 'word_id', 'id');
    }
    public function WordType()
    {
        return $this->belongsTo(WordType::class, 'word_type_id', 'id');
    }
}
