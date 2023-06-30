<?php

namespace App\Models;

use App\Models\Means;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Word extends Model
{
    use HasFactory;
    protected $table = 'words';

    protected $fillable = [
        'word_name',
        'pronunciations',
        'specialization_id',
        'synonymous',
        'antonyms',
    ];
    // protected $with = ['specialization'];
    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id', 'id');
    }
    public function means()
    {
        return $this->hasMany(Means::class, 'word_id', 'id');
    }
}
