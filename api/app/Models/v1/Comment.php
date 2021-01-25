<?php

namespace App\Models\v1;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int model_id
 * @property string model_type
 * @property int user_id
 * @property int parent_id
 * @property string title
 * @property string details
 * @property int state
 * @property int anonymity
 * @property int score
 */
class Comment extends Model
{
    public static $withoutAppends = true;
    const COMMENT_MODEL_TYPE= 'App\Models\v1\GoodIndentCommodity'; //评价类型: 商品
    const COMMENT_STATE_AUDIT = 0; //审核状态：待审核
    const COMMENT_STATE_PASS = 1; //审核状态：审核通过
    const COMMENT_STATE_LOST = 2; //审核状态：审核失败
    const COMMENT_ANONYMITY_YES = 1; //是否匿名：是
    const COMMENT_ANONYMITY_NO = 0; //是否匿名：否

    protected $appends = ['model_type_show'];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * 获取多张图片
     */
    public function resourcesMany(){
        return $this->morphMany('App\Models\v1\Resource', 'image');
    }

    /**
     * 获取评价的回复信息单条
     */
    public function Reply(){
        return $this->hasOne(Comment::class,'parent_id','id');
    }

    /**
     * 评价类型
     *
     */
    protected function getModelTypeShowAttribute()
    {
        if(isset($this->attributes['model_type'])){
            if(self::$withoutAppends){
                return '';
            }else{
                switch ($this->attributes['model_type']){
                    case self::COMMENT_MODEL_TYPE:
                        return '商品';
                        break;
                }
            }

        }
    }

    /**
     * 评价类型
     *
     */
    protected function getStateAttribute()
    {
        if(isset($this->attributes['state'])){
            if(self::$withoutAppends){
                return '';
            }else{
                switch ($this->attributes['state']){
                    case self::COMMENT_STATE_AUDIT:
                        return '待审核';
                        break;
                    case self::COMMENT_STATE_PASS:
                        return '审核通过';
                        break;
                    case self::COMMENT_STATE_LOST:
                        return '审核失败';
                        break;
                }
            }

        }
    }

    /**
     * 用户
     */
    public function User(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
