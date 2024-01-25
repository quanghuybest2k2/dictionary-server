<?php

namespace App\Repositories\ReviewsRepositoryService;

use App\Models\Review;
use App\Repositories\ReviewsRepositoryService\IReviewsRepository;

class ReviewsRepository implements IReviewsRepository
{
    /**
     * tạo đánh giá
     *
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        return Review::create([
            'user_id' => $data['user_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }
}
