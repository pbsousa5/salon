<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ConsumerBeanEvent extends Event
{
    use SerializesModels;
    
    /**
     * 消费者model
     * @var App\Salon\Consumer
     */
    public $consumer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($consumer, $use_bean_count)
    {
        $this->consumer = $consumer;
        $this->use_bean_count = $use_bean_count;#使用的美发币数量
    }
}