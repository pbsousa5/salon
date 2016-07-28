<?php

namespace App\Salon;

/**
 *
 *
 * @desc 门店Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="BusTime")
 */
class BusTime
{
    /**
     * @SWG\Property(name="morning_time", type="string", description="早上上班时间")
     * @var string
     */
    public $morning_time;
    
    /**
     * @SWG\Property(name="noon_time", type="string", description="中午下班时间")
     * @var string
     */

    public $noon_time;
    
    /**
     * @SWG\Property(name="afternoon_time", type="string", description="下午上班时间")
     * @var string
     */
    public $afternoon_time;

    /**
     * @SWG\Property(name="night_time", type="string", description="晚上下班时间")
     * @var string
     */
    protected $night_time;
}