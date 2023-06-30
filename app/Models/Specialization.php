<?php

namespace App\Models;

use App\Models\Word;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialization extends Model
{
    use HasFactory;

    protected $table = 'specializations';

    protected $fillable = [
        'specialization_name',
    ];

    public function words()
    {
        return $this->hasMany(Word::class);
    }
}
