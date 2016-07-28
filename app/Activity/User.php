<?php

namespace App\Activity;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 数据库
     * @var string
     */
    protected $connection = 'mysql2';
    
    /**
     * 数据库表
     * @var string
     */
    protected $table = 'user';
    
    /**
     * 允许批量赋值
     * @var unknown
     */
    protected $fillable = [
            'nick_name',
            'mobile',
            'price',
    ];
}
