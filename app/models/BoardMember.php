<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 13-12-10
 * Time: 下午6:46
 */

/**
 * Class BoardMember
 * board成员表，记录用户
 */
class BoardMember extends Model {

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

} 