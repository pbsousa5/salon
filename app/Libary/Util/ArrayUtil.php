<?php

namespace App\Libary\Util;

/**
 * 
 * 
 * @desc 数组处理工具
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月13日
 */
class ArrayUtil
{
    /**
     * 
     * @desc 检查数组是否存在空键，可以检测多维数组
     * @param array $input
     * @return boolean
     */
    public static function isEmptyKey(array $input)
    {
        while (list($key, $val) = each($input)) {
            if ('' == trim($key)) {
                return true;
            }
        }
        
        return false;
    }
}