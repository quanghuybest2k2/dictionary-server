<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\LoveRepositoryService\ILoveRepository;

class LoveController extends Controller
{
    private $iLoveRepository;
    public function __construct(
        ILoveRepository $iLoveRepository,
    ) {
        $this->iLoveRepository = $iLoveRepository;
    }
    public function saveLoveVocabulary(Request $request)
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

            $loveVocabularies = $this->iLoveRepository->createLoveVocabularies($request->all());

            if ($loveVocabularies) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm từ này vào mục yêu thích.',
                    'loveVocabularies' => $loveVocabularies
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Thêm thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
    public function destroyLoveVocabulary($english, $user_id)
    {
        $isDelete = $this->iLoveRepository->delete($english, $user_id);

        if (!$isDelete) {
            return response()->json([
                'status' => Response::HTTP_NO_CONTENT,
                'error' => 'Không thể xóa đánh dấu yêu thích!'
            ], Response::HTTP_NO_CONTENT);
        }
        return response()->json([
            'status' => Response::HTTP_ACCEPTED,
            'message' => 'Đã xóa đánh dấu yêu thích.'
        ], Response::HTTP_ACCEPTED);
    }
    public function saveLoveText(Request $request)
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

            $loveTexts = $this->iLoveRepository->createLoveTexts($request->all());

            if ($loveTexts) {
                return response()->json([
                    'status' => Response::HTTP_CREATED,
                    'message' => 'Đã thêm bản dịch này vào mục yêu thích.',
                    'loveTexts' => $loveTexts
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Thêm thất bại!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
    public function destroyLoveText(Request $request)
    {
        $isDelete = $this->iLoveRepository->deleteLoveText($request->english, $request->user_id);

        if (!$isDelete) {
            return response()->json([
                'status' => Response::HTTP_NO_CONTENT,
                'error' => 'Không thể xóa đánh dấu yêu thích!'
            ], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json([
                'status' => Response::HTTP_ACCEPTED,
                'message' => 'Đã xóa đánh dấu yêu thích.'
            ], Response::HTTP_ACCEPTED);
        }
    }
}
