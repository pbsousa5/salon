<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 登陆日志model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 */
class LoginLogoutLog extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'login_logout_logs';
    
    /**
     * 关闭自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'mobile',
            'user_id',
            'user_type',
            'user_ip',
            'source',
            'content',
            'created_at',
    ];
}
