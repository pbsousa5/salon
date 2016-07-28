<?php

namespace App\Salon\Extensions;

use Illuminate\Validation\Validator;
use App\Libary\Util\Validate;

/**
 * 
 * 
 * @desc 扩展验证类
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class SalonValidator extends Validator
{
    /**
     * 
     * 验证11位手机号码
     */
    public function validateMobile($attribute, $value)
    {
        if (is_null($value)) {
            return false;
        }
        
        return Validate::isMobile(trim($value));
    }
}