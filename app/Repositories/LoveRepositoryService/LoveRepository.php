<?php

namespace App\Repositories\LoveRepositoryService;

use Exception;
use App\Models\LoveText;
use App\Models\LoveVocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// https://github.com/quanghuybest2k2/DictionaryAppForIT/blob/main/rest-api/DictionaryAppForIT/UserControls/YeuThich/UC_YeuThich.cs

class LoveRepository implements ILoveRepository
{
    /**
     * Kiểm tra tồn tại
     *
     * @param Model $model
     * @param string $english
     * @param int $user_id
     * @return void
     */
    private function checkIfExist($model, $english, $user_id)
    {
        return $model::where('english', $english)
            ->where('user_id', $user_id)
            ->exists();
    }
    /**
     * Đếm số lượng bản ghi table
     *
     * @param string $model Tên lớp mô hình
     * @param int $user_id ID người dùng
     * @return int Số lượng bản ghi
     */
    private function countRecords($model, $user_id): int
    {
        return $model::where('user_id', $user_id)->count();
    }
    /**
     * Lấy tất cả record của bảng
     *
     * @param string $model tên model
     * @param int $user_id ID người dùng
     * @return \Illuminate\Database\Eloquent\Collection toàn bộ bản ghi
     */
    private function sortByModel($model, $user_id)
    {
        return $model::where('user_id', $user_id)
            ->orderBy('english', 'asc')
            ->get();
    }
    private function getLoveByModel($model, $user_id)
    {
        return $model::where('user_id', $user_id)->get();
    }
    private function deleteLoveVocabularyByUserId($user_id)
    {
        return LoveVocabulary::where('user_id', $user_id)->delete();
    }
    private function deleteLoveTextByUserId($user_id)
    {
        return LoveText::where('user_id', $user_id)->delete();
    }
    // select sum(AllCount) AS Tong_SoMucYeuThich from((select count(*) AS AllCount from love_vocabularies where user_id = '2' and english = 'firewall') union all (select count(*) AS AllCount from love_texts where user_id = 2 and english LIKE '%my%'))t;
    /**
     * Tìm kiếm tổng số mục yêu thích bằng từ vựng và bản dịch
     *
     * @param string $english
     * @param int $user_id
     * @return void
     */
    public function FindLoveByWordAndEnglish($english, $user_id)
    {
        $loveVocabularyCount = LoveVocabulary::where('user_id', $user_id)
            ->where('english', $english)
            ->count();

        $loveTextCount = LoveText::where('user_id', $user_id)
            ->where('english', 'like', '%' . $english . '%')
            ->count();

        $tongSoMucYeuThich = $loveVocabularyCount + $loveTextCount;

        return $tongSoMucYeuThich;
    }
    /**
     * Xóa hết record có trong 2 bảng
     *
     * @param int $user_id ID người dùng
     * @return void
     */
    public function deleteAllFavorite($user_id)
    {
        return $this->deleteLoveVocabularyByUserId($user_id) + $this->deleteLoveTextByUserId($user_id);
    }
    /**
     * Tính tổng số lượng bản ghi của cả hai loại.
     *
     * @param int $user_id ID người dùng
     * @return int Tổng số lượng bản ghi
     */
    public function TotalLoveItemOfUser($user_id): int
    {
        $loveVocabulary = $this->countLoveVocabulary($user_id);
        $loveText = $this->countLoveTexts($user_id);

        return $loveVocabulary + $loveText;
    }
    //======================================== Vocabulary ==================================
    /**
     * Đếm số lượng từ vựng yêu thích của người dùng.
     *
     * @param int $user_id ID người dùng
     * @return int Số lượng từ vựng yêu thích
     */
    private function countLoveVocabulary($user_id): int
    {
        return $this->countRecords(LoveVocabulary::class, $user_id);
    }
    /**
     * Lấy danh sách từ vựng yêu thích của người dùng.
     *
     * @param int $user_id ID người dùng
     * @return \Illuminate\Database\Eloquent\Collection Danh sách từ vựng yêu thích
     */
    private function getLoveVocabularies($user_id)
    {
        return $this->getLoveByModel(LoveVocabulary::class, $user_id);
    }
    /**
     * Hiển thị danh sách từ vựng yêu thích của người dùng.
     *
     * @param int $user_id ID người dùng
     * @return \Illuminate\Database\Eloquent\Collection Danh sách từ vựng yêu thích
     * @throws \Exception Nếu không có từ vựng yêu thích
     */
    public function displayLoveVocabulary($user_id)
    {
        $totalRecord = $this->countLoveVocabulary($user_id);

        if ($totalRecord > 0) {
            $loveVocabularies = $this->getLoveVocabularies($user_id);
            return $loveVocabularies;
        } else {
            throw new \Exception('Hiện tại chưa có từ vựng yêu thích!');
        }
    }
    /**
     * Hiển thị từ vựng yêu thích theo từ và id user
     *
     * @param string $english
     * @param int $user_id
     * @return void
     */
    public function displayLoveVocabularyByWord($english, $user_id)
    {
        $exist = $this->checkIfExist(LoveVocabulary::class, $english, $user_id);
        if ($exist) {
            return LoveVocabulary::where('english', $english)
                ->where('user_id', $user_id)
                ->get();
        } else {
            throw new \Exception('Không tồn tại từ vựng yêu thích này!');
        }
    }
    /**
     * Tạo mới một từ vựng yêu thích.
     *
     * @param array $data Dữ liệu gửi về server
     * @return void
     */
    public function createLoveVocabulary(array $data)
    {
        $exist = $this->checkIfExist(LoveVocabulary::class, $data['english'], $data['user_id']);
        if ($exist) {
            throw new \Exception('Đã tồn tại từ vựng yêu thích này!');
        } else {
            $LoveVocabulary = LoveVocabulary::create([
                'english' => $data['english'],
                'pronunciations' => $data['pronunciations'],
                'vietnamese' => $data['vietnamese'],
                'user_id' => $data['user_id'],
            ]);
            return $LoveVocabulary;
        }
    }
    /**
     * Xóa một từ vựng yêu thích của người dùng.
     *
     * @param string $english Từ vựng tiếng Anh cần xóa
     * @param int $user_id ID người dùng
     * @return void
     */
    public function deleteLoveVocabulary($english, $user_id)
    {
        return LoveVocabulary::where('english', $english)
            ->where('user_id', $user_id)
            ->delete();
    }
    /**
     * Sắp xếp từ vựng yêu thích theo asc
     *
     * @param int $user_id
     * @return void
     */
    public function sortByVocabulary($user_id)
    {
        if ($this->countLoveVocabulary($user_id) > 0) {
            return $this->sortByModel(LoveVocabulary::class, $user_id);
        } else {
            throw new \Exception("Hiện tại chưa có từ vựng yêu thích!");
        }
    }
    /**
     * Cập nhật ghi chú của từ vựng
     *
     * @param int $id ID của từ vựng
     * @param int $user_id ID của người dùng
     * @param array $Note Ghi chú gửi kèm
     * @return void
     */
    public function updateVocabulary($id, $user_id, $Note)
    {
        return LoveVocabulary::where('id', $id)
            ->where('user_id', $user_id)
            ->update(['Note' => $Note]);
    }
    //======================================== Text ==================================
    /**
     * Đếm số lượng văn bản yêu thích của người dùng.
     *
     * @param int $user_id ID người dùng
     * @return int Số lượng văn bản yêu thích
     */
    private function countLoveTexts($user_id): int
    {
        return $this->countRecords(LoveText::class, $user_id);
    }
    /**
     * Lấy danh sách văn bản yêu thích của người dùng.
     *
     * @param int $user_id ID người dùng
     * @return \Illuminate\Database\Eloquent\Collection Danh sách văn bản yêu thích
     */
    private function getLoveTexts($user_id)
    {
        return $this->getLoveByModel(LoveText::class, $user_id);
    }
    /**
     * Hiển thị danh sách văn bản yêu thích của người dùng.
     *
     * @param int $user_id ID người dùng
     * @return \Illuminate\Database\Eloquent\Collection Danh sách văn bản yêu thích
     * @throws \Exception Nếu không có văn bản yêu thích
     */
    public function displayLoveText($user_id)
    {
        $totalRecord = $this->countLoveTexts($user_id);

        if ($totalRecord > 0) {
            $loveTexts = $this->getLoveTexts($user_id);
            return $loveTexts;
        } else {
            throw new \Exception('Hiện tại chưa có văn bản yêu thích!');
        }
    }
    /**
     * Hiển thị văn bản yêu thích theo từ và id user
     *
     * @param string $english
     * @param int $user_id
     * @return void
     */
    public function displayLoveTextByWord($english, $user_id)
    {
        // $exist = $this->checkIfExist(LoveText::class, $english, $user_id);
        // if ($exist) {
        return LoveText::where('english', 'LIKE', '%' . $english . '%')
            ->where('user_id', $user_id)
            ->get();
        // } else {
        //     throw new \Exception('Không tồn tại bản dịch yêu thích này!');
        // }
    }
    /**
     * Tạo mới một bản dịch yêu thích.
     *
     * @param array $data Dữ liệu gửi về server
     * @return void
     */
    public function createLoveTexts(array $data)
    {
        $exist = $this->checkIfExist(LoveText::class, $data['english'], $data['user_id']);
        if ($exist) {
            throw new \Exception('Đã tồn tại bản dịch yêu thích này!');
        } else {
            $LoveText = LoveText::create([
                'english' => $data['english'],
                'vietnamese' => $data['vietnamese'],
                'user_id' => $data['user_id'],
            ]);
            return $LoveText;
        }
    }
    /**
     * Xóa một văn bản yêu thích của người dùng.
     *
     * @param string $english Từ vựng tiếng Anh cần xóa
     * @param int $user_id ID người dùng
     * @return void
     */
    public function deleteLoveText($english, $user_id)
    {
        return LoveText::where('english', $english)
            ->where('user_id', $user_id)
            ->delete();
    }
    /**
     * Sắp xếp bản dịch yêu thích theo asc
     *
     * @param int $user_id
     * @return void
     */
    public function sortByText($user_id)
    {
        if ($this->countLoveTexts($user_id) > 0) {
            return $this->sortByModel(LoveText::class, $user_id);
        } else {
            throw new \Exception("Hiện tại chưa có văn bản yêu thích!");
        }
    }
    /**
     * Cập nhật ghi chú của bản dịch
     *
     * @param int $id ID của bản dịch
     * @param int $user_id ID của người dùng
     * @param array $Note Ghi chú gửi kèm
     * @return void
     */
    public function updateText($id, $user_id, $Note)
    {
        return LoveText::where('id', $id)
            ->where('user_id', $user_id)
            ->update(['Note' => $Note]);
    }
}
