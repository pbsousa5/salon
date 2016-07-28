<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher;

class PusherJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $channelId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($channelId)
    {
        $this->channelId = $channelId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 消息内容.
        $message = array (
                // 消息的标题.
                'title' => 'Hi!.',
                // 消息内容
                'description' => "hello!, this message from baidu push service."
        );
        // 设置消息类型为 通知类型.
        $opts = array (
                'msg_type' => 1
        );
        // 向目标设备发送一条消息
        $rs = Pusher::pushMsgToSingleDevice($this->channelId, $message, $opts);
        
        file_put_contents('D:/push_log.txt', json_encode($rs));
    }
}
