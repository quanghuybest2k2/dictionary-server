<?php

namespace App\Repositories\WordRepositoryService;

use App\Models\Word;
use Phonetic\Phonetics;
use App\Services\UserRoleManager;
use Illuminate\Support\Facades\DB;

class WordRepository implements IWordRepository
{
    private $userRoleManager;

    public function __construct(UserRoleManager $userRoleManager)
    {
        $this->userRoleManager = $userRoleManager;
    }

    public function getAll()
    {
        return Word::where('status', 1)->get();
    }
    public function getUnApproved()
    {
        return Word::where('status', 0)->get();
    }
    public function getSuggest($specializationId)
    {
        return Word::where('specialization_id', $specializationId)->where('status', 1)->get(); //->pluck('word_name')
    }
    public function getRandomWord()
    {
        return DB::table('words')
            ->join('means', 'words.id', '=', 'means.word_id')
            ->join('word_types', 'means.word_type_id', '=', 'word_types.id')
            ->join('specializations', 'words.specialization_id', '=', 'specializations.id')
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
    // check if exists
    private function wordExists($wordName, $status): bool
    {
        return Word::where('word_name', $wordName)
            ->where('status', $status)
            ->exists();
    }
    /*
    * user thêm từ cần phê duyệt, admin thì không cần nên dùng role để kiểm tra quyền thêm
    */
    // store word
    public function createWord(array $data): array
    {
        $word_name = $data['word_name'];
        $currentUserRole = $this->userRoleManager->getCurrentUserRole(); // 1=admin
        if ($this->wordExists($word_name, $currentUserRole)) {
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
            'status' => $currentUserRole,
        ]);

        return $word->toArray();
    }
}
