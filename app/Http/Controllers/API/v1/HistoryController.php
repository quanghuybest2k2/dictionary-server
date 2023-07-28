<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Models\WordLookupHistory;
use App\Http\Controllers\Controller;
use App\Models\LoveText;
use App\Models\TranslateHistory;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\HistoriesRepositoryService\IHistoriesRepository;

class HistoryController extends Controller
{
    private $historiesRepository;
    public function __construct(
        IHistoriesRepository $historiesRepository,
    ) {
        $this->historiesRepository = $historiesRepository;
    }
    public function checkIfExist(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'english' => 'required|max:400',
                'user_id' => 'required|integer|min:1',
            ],
            [
                'required' => 'Vui lòng nhập :attribute.',
                'max' => ':attribute không được vượt quá :max ký tự.',
                'user_id.integer' => ':attribute phải là số nguyên dương.',
            ],
            [
                'english' => 'Từ khóa tiếng anh',
                'user_id' => 'Id người dùng',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ]);
        } else {
            $word = $this->historiesRepository->checkIfExist(new WordLookupHistory(), $request->english, $request->user_id);
            $loveText = $this->historiesRepository->checkIfExist(new LoveText(), $request->english, $request->user_id);

            return response()->json([
                'status' => Response::HTTP_OK,
                'word' => $word,
                'loveText' => $loveText
            ]);
        }
    }
    // ====================== WordLookupHistory ============================
    public function storeWordLookupHistory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'english' => 'required|max:400',
                'pronunciations' => 'required|max:100',
                'vietnamese' => 'required|max:400',
                'user_id' => 'required|integer|min:1',
            ],
            [
                'required' => 'Vui lòng nhập :attribute.',
                'max' => ':attribute không được vượt quá :max ký tự.',
                'integer' => ':attribute phải là số nguyên.',
                'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            ],
            [
                'english' => 'Tiếng anh',
                'pronunciations' => 'Phiên âm',
                'vietnamese' => 'Tiếng việt',
                'user_id' => 'Id người dùng',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ]);
        } else {

            $wordLookupHistory = $this->historiesRepository->createWordLookupHistory($request->all());

            if ($wordLookupHistory) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm từ này vào lịch sử.',
                    'wordLookup' => $wordLookupHistory
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Thêm thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
    public function getWordLookupHistory($user_id)
    {
        try {
            $WordLookupHistory = $this->historiesRepository->getWordLookupHistory($user_id);
            if ($WordLookupHistory) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'WordLookupHistory' => $WordLookupHistory,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'error' => 'Lấy thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // ====================== TranslateHistory =============================
    public function loadTranslateHistoryByUser($user_id)
    {
        try {
            $translateHistory =  $this->historiesRepository->loadAllTranslateHistory($user_id);

            if ($translateHistory) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'translateHistory' => $translateHistory
                ]);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'error' => 'Lấy thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function storeTranslateHistory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'english' => 'required|max:400',
                'vietnamese' => 'required|max:400',
                'user_id' => 'required|integer|min:1',
            ],
            [
                'required' => 'Vui lòng nhập :attribute.',
                'max' => ':attribute không được vượt quá :max ký tự.',
                'integer' => ':attribute phải là số nguyên.',
                'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            ],
            [
                'english' => 'Tiếng anh',
                'vietnamese' => 'Tiếng việt',
                'user_id' => 'Id người dùng',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'validator_errors' => $validator->messages(),
            ]);
        } else {

            $translateHistory = $this->historiesRepository->createTranslateHistory($request->all());
            // $existingRecord = TranslateHistory::where('english', $request->english)
            //     ->where('vietnamese', $request->vietnamese)
            //     ->where('user_id', $request->user_id)
            //     ->first();

            if ($translateHistory) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm bản dịch này vào lịch sử.',
                    'wordLookup' => $translateHistory
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Thêm thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
    // delete all record
    public function destroy(Request $request)
    {
        try {
            $isSuccess = $this->historiesRepository->deleteAllTranslateHistory($request->user_id);
            if ($isSuccess) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Đã xóa toàn bộ bản dịch.'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Không tìm thấy bản dịch'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function destroyById(Request $request)
    {
        try {
            $isDelete = $this->historiesRepository->deleteByIdTranslateHistory($request->user_id, $request->id);
            if ($isDelete) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Đã xóa bản dịch.'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Không tìm thấy bản dịch'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
