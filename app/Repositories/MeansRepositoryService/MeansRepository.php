<?php

namespace App\Repositories\MeansRepositoryService;

use App\Models\Means;
use App\Services\UserRoleManager;

class MeansRepository implements IMeansRepository
{
    private $userRoleManager;

    public function __construct(UserRoleManager $userRoleManager)
    {
        $this->userRoleManager = $userRoleManager;
    }
    public function findByWordId($wordId)
    {
        return Means::where('word_id', $wordId)->first();
    }
    // check if exists
    private function meanExists($wordId, $wordTypeId, $means, $status): bool
    {
        return Means::where('word_id', $wordId)
            ->where('word_type_id', $wordTypeId)
            ->where('means', $means)
            ->where('status', $status)
            ->exists();
    }
    /*
    * user thêm nghĩa song song với từ cần phê duyệt, admin thì không cần nên dùng role để kiểm tra quyền thêm
    */
    // store mean
    public function createMean(array $data): array
    {
        $currentUserRole = $this->userRoleManager->getCurrentUserRole(); // 1=admin

        $wordId = $data['word_id'];
        $wordTypeId = $data['word_type_id'];
        $means = $data['means'];

        if ($this->meanExists($wordId, $wordTypeId, $means, $currentUserRole)) {
            throw new \Exception("Dữ liệu này bạn đã gửi đi rồi!");
        }
        $word = Means::create([
            'word_id' => $data['word_id'],
            'word_type_id' => $data['word_type_id'],
            'means' => $means,
            'description' => $data['description'] ?? '',
            'example' => $data['example'] ?? '',
            'status' => $currentUserRole,
        ]);

        return $word->toArray();
    }
}
