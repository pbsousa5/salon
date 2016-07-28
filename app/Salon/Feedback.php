<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 反馈model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="Feedback")
 */
class Feedback extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'feedbacks';
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = ['status'];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="mobile", type="string", required=true, description="留言的手机号码")
     * @var string
     */
    private $mobile;
    
    /**
     * @SWG\Property(name="source", type="string", required=true, description="信息来源")
     * @var string
     */
    private $source;
    
    /**
     * @SWG\Property(
     *  name="user_type",
     *  type="string",
     *  required=true,
     *  description="用户类型",
     *  enum="{'consumer':'用户','supplier':'门店','barber':'理发师','other':'其他'}"
     * )
     * @var string
     */
    private $user_type;
    
    /**
     * @SWG\Property(name="feedback_txt", type="string", required=true, description="反馈内容")
     * @var string
     */
    private $feedback_txt;
    
    /**
     * @SWG\Property(name="feedback_imgs", type="string", description="反馈的图片")
     * @var string
     */
    private $feedback_imgs;
    
    /**
     * @SWG\Property(name="status", type="integer", description="状态", enum="{'0':'未读','1':'已读'}")
     * @var int
     */
    private $status;
}
