<?php

namespace App\Repositories\SpecializationRepositoryService;

use App\Models\Specialization;
use Illuminate\Support\Facades\DB;

class SpecializationRepository implements ISpecializationRepository
{
    public function getAll()
    {
        return Specialization::all();
    }
    public function find($id)
    {
        return Specialization::find($id);
    }
    // hiển thị theo chuyên ngành
    public function getBySpecializationId($specializationId)
    {
        return DB::table('words')
            ->join('means', 'words.id', '=', 'means.word_id')
            ->join('word_types', 'means.word_type_id', '=', 'word_types.id')
            ->join('specializations', 'words.specialization_id', '=', 'specializations.id')
            ->where('words.specialization_id', '=', $specializationId)
            ->where('words.status', 1)
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
    // tìm theo chuyên ngành
    public function findBySpecialty($searched_word, $specialization_id)
    {
        return DB::table('words')
            ->join('means', 'words.id', '=', 'means.word_id')
            ->join('word_types', 'means.word_type_id', '=', 'word_types.id')
            ->join('specializations', 'words.specialization_id', '=', 'specializations.id')
            ->where('word_name', 'like', '%' . $searched_word . '%')
            ->where('words.specialization_id', '=', $specialization_id)
            ->where('words.status', 1)
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
