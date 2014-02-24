<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 13-12-10
 * Time: 下午6:46
 */

/**
 * Class CardMember
 * Card成员表，记录用户
 * @property int $card_id Card id
 * @property int $user_id user id
 */
class CardMember extends Model {

    /**
     * 关系定义，从属于User，获取相关用户模型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('User');
    }

    /**
     * 获取用户模型
     * @return mixed
     */
    public function getUsername(){
        return $this->user->username;
    }

    /**
     * 验证规则
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator() {
        return Validator::make(
            $this->getAttributes(), array(
                'card_id' => 'required|unique_with:card_members,card_id,user_id',
                'user_id' => 'required',
        ));
    }
} 