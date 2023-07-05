<?php

namespace App\Repositories\WordRepositoryService;

use App\Models\Word;
use Illuminate\Support\Facades\DB;

class WordRepository implements IWordRepository
{
    public function getAll()
    {
        return Word::all();
    }
    public function getRandomWord()
    {
        return DB::table('words')
            ->join('means', 'words.id', '=', 'means.word_id')
            ->join('word_types', 'means.word_type_id', '=', 'word_types.id')
            ->join('specializations', 'words.specialization_id', '=', 'specializations.id')
            ->select(
                'words.word_name',
                'word_types.type_name',
                'words.pronunciations',
                'specializations.specialization_name',
                'means.means',
                'means.description',
                'means.example',
                'words.synonymous',
                'words.antonyms',
            )
            ->inRandomOrder()
            ->first();
    }
    public function searchByKeyword($keyword)
    {
        return DB::table('words')
            ->join('means', 'words.id', '=', 'means.word_id')
            ->join('word_types', 'means.word_type_id', '=', 'word_types.id')
            ->join('specializations', 'words.specialization_id', '=', 'specializations.id')
            ->where('word_name', '=', $keyword)
            ->select(
                'words.word_name',
                'word_types.type_name',
                'words.pronunciations',
                'specializations.specialization_name',
                'means.means',
                'means.description',
                'means.example',
                'words.synonymous',
                'words.antonyms',
            )
            ->get();
    }
}
