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
 * @property int $board_id board id
 * @property int $user_id user id
 * @property bool $is_admin 是否为管理员
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