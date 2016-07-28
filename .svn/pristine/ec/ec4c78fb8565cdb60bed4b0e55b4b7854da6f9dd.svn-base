<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Salon\Consumer;
use App\Salon\Review;

/**
 * 
 * 
 * @desc 点击有用时，增加用户等级积分，每次增加1分，最多增加10分
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月16日
 */
class MarkUserfulAddScoreEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($review_id)
    {
        $review = Review::where('id', $review_id)->first();
        $this->consumer = Consumer::where('id', $review->consumer_id)->first();
        $this->review_id = $review_id;
    }
}
