<?php

namespace App\Repositories\WordRepositoryService;

use App\Models\Word;
use Phonetic\Phonetics;
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
    // check if exists
    private function wordExists($wordName)
    {
        return Word::where('word_name', $wordName)->exists();
    }
    // store word
    public function createWord(array $data): array
    {
        $word_name = $data['word_name'];
        if ($this->wordExists($word_name)) {
            throw new \Exception('Từ này đã tồn tại trong từ điển.');
        }
        $phoneticSymbols = Phonetics::symbols($word_name, 'array');
        $pronunciation = '';

        foreach ($phoneticSymbols as $wordPhonetics) {
            // phần tử đầu tiên của mảng
            $firstPhoneticSymbol = reset($wordPhonetics);
            $pronunciation = $firstPhoneticSymbol;
            break;
        }
        $word = Word::create([
            'word_name' => $word_name,
            'pronunciations' => $pronunciation,
            'specialization_id' => $data['specialization_id'],
            'synonymous' => $data['synonymous'],
            'antonyms' => $data['antonyms'],
        ]);

        return $word->toArray();
    }
}
